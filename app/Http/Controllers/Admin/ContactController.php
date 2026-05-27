<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ContactController extends Controller
{
    public function index(Request $request)
    {
        $query = DB::table('LienHe')->whereNull('deleted_at');

        // Search filter
        if ($request->filled('keyword')) {
            $keyword = $request->get('keyword');
            $query->where(function ($q) use ($keyword) {
                $q->where('HoTen', 'like', "%{$keyword}%")
                  ->orWhere('Email', 'like', "%{$keyword}%")
                  ->orWhere('TieuDe', 'like', "%{$keyword}%")
                  ->orWhere('NoiDung', 'like', "%{$keyword}%");
            });
        }

        $contacts = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();

        return view('admin.lien-he.index', compact('contacts'));
    }

    public function markAsRead($id)
    {
        DB::table('LienHe')->where('Id', $id)->update([
            'TrangThai' => 1,
            'updated_at' => now()
        ]);

        return redirect()->back()->with('success', 'Đã đánh dấu là đã đọc.');
    }

    public function destroy($id)
    {
        DB::table('LienHe')->where('Id', $id)->update([
            'deleted_at' => now(),
            'updated_at' => now()
        ]);

        return redirect()->back()->with('success', 'Đã xóa tin nhắn liên hệ thành công.');
    }
}
