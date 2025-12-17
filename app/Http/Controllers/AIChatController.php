<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Disease;
use App\Models\Service;
use App\Models\Department;

class AIChatController extends Controller
{
    public function chat(Request $request)
    {
        $message = trim((string) $request->input('message', ''));

        if ($message === '') {
            return response()->json([
                'reply' => 'Vui lòng mô tả triệu chứng bạn đang gặp.',
                'suggestions' => [
                    'diseases' => [],
                    'departments' => [],
                    'services' => [],
                ],
            ], 422);
        }

        // 1️⃣ GỌI AI → LẤY TÊN BỆNH
        $aiDiseases = $this->askAIForDiseases($message);

        if (empty($aiDiseases)) {
            return response()->json([
                'reply' => 'Hệ thống chưa xác định được bệnh phù hợp. Vui lòng mô tả rõ hơn triệu chứng.',
                'suggestions' => [
                    'diseases' => [],
                    'departments' => [],
                    'services' => [],
                ],
            ]);
        }

        // 2️⃣ MAP BỆNH AI → DB (dùng LIKE để không yêu cầu trùng 100%)
        $diseases = Disease::with('department')
            ->where(function ($q) use ($aiDiseases) {
                foreach ($aiDiseases as $name) {
                    $q->orWhere('name', 'like', '%' . $name . '%');
                }
            })
            ->get();

        // 3️⃣ GỢI Ý KHOA
        $departments = $diseases
            ->pluck('department')
            ->filter()
            ->unique('id')
            ->values()
            ->map(fn ($d) => $d->only(['id', 'name']));

        // 4️⃣ GỢI Ý DỊCH VỤ (nếu chưa có khoa nào match thì không filter theo department)
        if ($departments->isNotEmpty()) {
            $services = Service::whereIn(
                'department_id',
                $departments->pluck('id')
            )
                ->limit(6)
                ->get(['id', 'name', 'price']);
        } else {
            $services = collect();
        }

        Log::info('AIChat mapped diseases', [
            'message' => $message,
            'ai_diseases' => $aiDiseases,
            'db_diseases_count' => $diseases->count(),
            'departments_count' => $departments->count(),
            'services_count' => $services->count(),
        ]);

        return response()->json([
            'reply' =>
                'Dựa trên triệu chứng bạn cung cấp, hệ thống AI gợi ý một số bệnh nghi ngờ. ' .
                'Kết quả chỉ mang tính tham khảo, bạn nên đến bệnh viện để được chẩn đoán chính xác.',
            'suggestions' => [
                'diseases' => $diseases->map(fn ($d) => [
                    'id' => $d->id,
                    'name' => $d->name,
                    'department' => $d->department?->name,
                ]),
                'departments' => $departments,
                'services' => $services,
            ],
        ]);
    }

    /**
     * GỌI AI ĐỂ LẤY DANH SÁCH BỆNH
     */
    private function askAIForDiseases(string $message): array
    {
        try {
            $res = Http::withToken(config('services.groq.key'))
                ->timeout(30)
                ->post('https://api.groq.com/openai/v1/chat/completions', [
                    'model' => 'llama-3.1-8b-instant',
                    'messages' => [
                        [
                            'role' => 'system',
                            'content' =>
                                'Bạn là trợ lý y tế. ' .
                                'Chỉ trả về JSON: {"diseases":["Tên bệnh"]}. ' .
                                'Không giải thích. Không chẩn đoán.'
                        ],
                        [
                            'role' => 'user',
                            'content' => $message
                        ],
                    ],
                ]);

            if (! $res->successful()) {
                Log::warning('AI disease API failed', [
                    'status' => $res->status(),
                    'body' => $res->body(),
                ]);
                return [];
            }

            $content = data_get($res->json(), 'choices.0.message.content');
            Log::info('AI disease raw content', ['content' => $content]);

            $json = json_decode($content, true);
            if (! is_array($json)) {
                Log::warning('AI disease content not valid JSON', ['content' => $content]);
                return [];
            }

            return collect($json['diseases'] ?? [])
                ->filter()
                ->unique()
                ->take(5)
                ->values()
                ->all();

        } catch (\Throwable $e) {
            Log::error('AI disease suggestion failed', [
                'error' => $e->getMessage(),
            ]);
            return [];
        }
    }
}
