<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Service;
use App\Models\Department;

class AIChatController extends Controller
{
    public function chat(Request $request)
    {
        $message = $request->input('message');

        try {
            $response = Http::withToken(env('OPENAI_API_KEY'))
                ->post('https://api.openai.com/v1/chat/completions', [
                    'model' => 'gpt-4-turbo',
                    'messages' => [
                        ['role' => 'system', 'content' => 'Bạn là bác sĩ tư vấn bệnh viện, có nhiệm vụ gợi ý khoa khám và dịch vụ phù hợp dựa trên triệu chứng. Trả lời ngắn gọn, rõ ràng.'],
                        ['role' => 'user', 'content' => $message],
                    ],
                ]);

            $reply = data_get($response->json(), 'choices.0.message.content', 'Xin lỗi, tôi chưa hiểu câu hỏi của bạn.');
        } catch (\Throwable $e) {
            $reply = 'Xin lỗi, hiện không thể kết nối AI. Vui lòng thử lại sau.';
        }

        $keywords = collect(preg_split('/\s+/u', mb_strtolower($message)))
            ->filter(fn($w) => mb_strlen(preg_replace('/[^\p{L}\p{N}]+/u', '', $w)) >= 3)
            ->unique()
            ->values();

        $services = collect();
        $departments = collect();

        if ($keywords->isNotEmpty()) {
            $services = Service::query()
                ->when(true, function ($q) use ($keywords) {
                    $q->where(function ($q2) use ($keywords) {
                        foreach ($keywords as $kw) {
                            $q2->orWhere('name', 'like', "%$kw%")
                               ->orWhere('description', 'like', "%$kw%");
                        }
                    });
                })
                ->limit(6)
                ->get(['id', 'name', 'price']);

            $departments = Department::query()
                ->where(function ($q) use ($keywords) {
                    foreach ($keywords as $kw) {
                        $q->orWhere('name', 'like', "%$kw%")
                          ->orWhere('description', 'like', "%$kw%");
                    }
                })
                ->limit(6)
                ->get(['id', 'name']);
        }

        return response()->json([
            'reply' => $reply,
            'suggestions' => [
                'services' => $services,
                'departments' => $departments,
            ],
        ]);
    }
}
