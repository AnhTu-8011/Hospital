<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    /**
     * ============================
     * HIỂN THỊ GIAO DIỆN CHAT
     * ============================
     * - Nếu người đăng nhập là admin → hiển thị danh sách người dùng (user) để chọn và trò chuyện.
     * - Nếu người đăng nhập là user → tự động chat với admin đầu tiên trong hệ thống.
     */
    public function index()
    {
        $user = Auth::user(); // Lấy thông tin người dùng hiện tại

        // Trường hợp người dùng hiện tại là admin
        if ($user->role === 'admin') {
            // Lấy danh sách tất cả user (trừ chính admin)
            $users = User::where('id', '!=', $user->id)
                         ->where('role', 'user')
                         ->get();

            // Trả về view giao diện chat cho admin, truyền danh sách users
            return view('chat.admin', compact('users'));
        }

        // Trường hợp người dùng là user thông thường
        // -> Mặc định chỉ có thể chat với admin
        $admin = User::where('role', 'admin')->first();

        // Trả về view chat cho user, truyền id của admin để xác định người nhận
        return view('chat.user', ['receiverId' => $admin->id]);
    }

    /**
     * ============================
     * GỬI TIN NHẮN
     * ============================
     * - Xử lý khi người dùng gửi tin nhắn.
     * - Dữ liệu tin nhắn được lưu vào bảng `messages`.
     */
    public function sendMessage(Request $request)
    {
        // Kiểm tra dữ liệu hợp lệ
        $request->validate([
            'receiver_id' => 'required|exists:users,id', // ID người nhận phải tồn tại
            'message' => 'required|string',              // Nội dung tin nhắn là chuỗi ký tự
        ]);

        // Lưu tin nhắn vào CSDL
        Message::create([
            'sender_id' => Auth::id(),             // ID người gửi = user đang đăng nhập
            'receiver_id' => $request->receiver_id, // ID người nhận được truyền từ request
            'message' => $request->message,         // Nội dung tin nhắn
        ]);

        // Trả về phản hồi JSON cho AJAX
        return response()->json(['status' => 'success']);
    }

    /**
     * ============================
     * LẤY DANH SÁCH TIN NHẮN
     * ============================
     * - Lấy toàn bộ tin nhắn giữa người dùng hiện tại và người được chọn (receiver).
     * - Sắp xếp theo thời gian tăng dần (tin nhắn cũ trước, mới sau).
     */
    public function getMessages($receiverId)
    {
        $messages = Message::where(function ($q) use ($receiverId) {
                // Điều kiện: user hiện tại là người gửi và receiver là người nhận
                $q->where('sender_id', Auth::id())
                  ->where('receiver_id', $receiverId);
            })
            ->orWhere(function ($q) use ($receiverId) {
                // Điều kiện ngược lại: receiver gửi và user hiện tại là người nhận
                $q->where('sender_id', $receiverId)
                  ->where('receiver_id', Auth::id());
            })
            ->orderBy('created_at', 'asc') // Sắp xếp theo thứ tự thời gian
            ->get();

        // Trả về danh sách tin nhắn dạng JSON cho frontend
        return response()->json($messages);
    }
}
