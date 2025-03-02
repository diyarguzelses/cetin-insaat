<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BoardOfDirector;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class BoardOfDirectorController extends Controller {

    /**
     * Yönetim kurulu üyelerinin listeleme sayfasını göster.
     */
    public function index() {
        return view('admin.board_of_directors.index');
    }

    /**
     * DataTable için yönetim kurulu üyelerini getir.
     */
    public function getData() {
        $directors = BoardOfDirector::select(['id', 'name', 'biography', 'image', 'order']);

        return DataTables::of($directors)
            ->editColumn('order', function ($director) {
                return $director->order;
            })
            ->addColumn('actions', function ($director) {
                return '
                <button class="btn btn-primary btn-sm edit-director"
                    data-id="'.$director->id.'"
                    data-name="'.$director->name.'"
                    data-biography="'.$director->biography.'">
                    Düzenle
                </button>
                <button class="btn btn-danger btn-sm delete-director" data-id="'.$director->id.'">
                    Sil
                </button>
            ';
            })
            ->rawColumns(['actions'])
            ->make(true);
    }

    /**
     * Yeni yönetim kurulu üyesi ekleme formunu göster.
     */
    public function create() {
        return view('admin.board_of_directors.create');
    }

    /**
     * Yeni yönetim kurulu üyesini veritabanına kaydet.
     */
    public function store(Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'biography' => 'required|string',
            'image' => 'nullable|image|max:2048',
        ]);

        $imageName = null;
        if ($request->hasFile('image')) {
            $imageName = time().'.'.$request->image->extension();
            $request->image->move(public_path('uploads/board_of_directors'), $imageName);
        }

        $maxOrder = BoardOfDirector::max('order');
        $order = $maxOrder ? $maxOrder + 1 : 1;

        BoardOfDirector::create([
            'name' => $request->name,
            'biography' => $request->biography,
            'image' => $imageName,
            'order' => $order,
        ]);

        return response()->json(['success' => true, 'message' => 'Yönetim kurulu üyesi eklendi.']);
    }

    /**
     * Yönetim kurulu üyesini düzenleme sayfasını göster.
     */
    public function edit($id) {
        $director = BoardOfDirector::findOrFail($id);
        return view('admin.board_of_directors.edit', compact('director'));
    }

    /**
     * Yönetim kurulu üyesini güncelle.
     */
    public function update(Request $request, $id) {
        $director = BoardOfDirector::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'biography' => 'required|string',
            'image' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            // Eski resmi sil
            if ($director->image) {
                $oldImagePath = public_path('uploads/board_of_directors/'.$director->image);
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }

            $imageName = time().'.'.$request->image->extension();
            $request->image->move(public_path('uploads/board_of_directors'), $imageName);
            $director->image = $imageName;
        }

        $director->update([
            'name' => $request->name,
            'biography' => $request->biography,
            'image' => $director->image
        ]);

        return response()->json(['success' => true, 'message' => 'Yönetim kurulu üyesi güncellendi.']);
    }

    /**
     * Yönetim kurulu üyesini sil.
     */
    public function destroy($id) {
        $director = BoardOfDirector::findOrFail($id);
        $deletedOrder = $director->order;

        if ($director->image) {
            $imagePath = public_path('uploads/board_of_directors/' . $director->image);
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }

        $director->delete();

        BoardOfDirector::where('order', '>', $deletedOrder)->decrement('order');

        return response()->json(['success' => true, 'message' => 'Yönetim kurulu üyesi silindi.']);
    }

    /**
     * Yönetim kurulu üyesinin resmini sil.
     */
    public function deleteImage($id) {
        $director = BoardOfDirector::findOrFail($id);

        if ($director->image) {
            $imagePath = public_path('uploads/board_of_directors/'.$director->image);
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
            $director->image = null;
            $director->save();

            return response()->json(['success' => true, 'message' => 'Resim silindi.']);
        }

        return response()->json(['success' => false, 'message' => 'Silinecek resim bulunamadı.'], 404);
    }

    /**
     * Yönetim kurulu üyesi sıralamasını güncelle.
     */
    public function updateOrder(Request $request) {
        $orders = $request->orders;

        if (!is_array($orders) || empty($orders)) {
            return response()->json(['success' => false, 'message' => 'Sıralama verisi bulunamadı.'], 400);
        }

        foreach ($orders as $orderData) {
            if (isset($orderData['id']) && isset($orderData['order'])) {
                $director = BoardOfDirector::find($orderData['id']);
                if ($director) {
                    $director->order = $orderData['order'];
                    $director->save();
                }
            }
        }

        return response()->json(['success' => true, 'message' => 'Yönetim kurulu sıralaması başarıyla güncellendi.']);
    }
}
