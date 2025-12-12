<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Medicine;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MedicineController extends Controller
{
    public function index()
    {
        $medicines = Medicine::orderBy('name')->paginate(15);

        return view('admin.medicines.index', compact('medicines'));
    }

    public function create()
    {
        return view('admin.medicines.create');
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);

        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        Medicine::create($data);

        return redirect()->route('admin.medicines.index')
            ->with('success', 'Thêm thuốc thành công.');
    }

    public function edit(Medicine $medicine)
    {
        return view('admin.medicines.edit', compact('medicine'));
    }

    public function update(Request $request, Medicine $medicine)
    {
        $data = $this->validateData($request, $medicine->id);

        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        $medicine->update($data);

        return redirect()->route('admin.medicines.index')
            ->with('success', 'Cập nhật thuốc thành công.');
    }

    public function destroy(Medicine $medicine)
    {
        $medicine->delete();

        return redirect()->route('admin.medicines.index')
            ->with('success', 'Xóa thuốc thành công.');
    }

    protected function validateData(Request $request, ?int $id = null): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'generic_name' => ['nullable', 'string', 'max:255'],
            'brand_name' => ['nullable', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255'],
            'category' => ['nullable', 'string', 'max:255'],
            'dosage_form' => ['nullable', 'string', 'max:255'],
            'strength' => ['nullable', 'string', 'max:255'],
            'unit' => ['nullable', 'string', 'max:255'],
            'price' => ['nullable', 'numeric', 'min:0'],
            'stock' => ['nullable', 'integer', 'min:0'],
            'min_stock' => ['nullable', 'integer', 'min:0'],
            'indications' => ['nullable', 'string'],
            'contraindications' => ['nullable', 'string'],
            'side_effects' => ['nullable', 'string'],
            'interactions' => ['nullable', 'string'],
            'dosage' => ['nullable', 'string'],
            'usage' => ['nullable', 'string'],
            'note' => ['nullable', 'string'],
            'manufacturer' => ['nullable', 'string', 'max:255'],
            'origin' => ['nullable', 'string', 'max:255'],
            'barcode' => ['nullable', 'string', 'max:255'],
            'is_prescription' => ['nullable', 'boolean'],
        ] + [
            'is_prescription' => $request->has('is_prescription'),
        ]);
    }
}
