<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ChatController extends Controller
{
    /**
     * Lấy user hiện tại theo đúng guard đang đăng nhập.
     * - Admin đăng nhập bằng guard web_admin.
     * - Người dùng/patient đăng nhập bằng guard mặc định web.
     *
     * @return \App\Models\User|null
     */
    private function authUser(): ?User
    {
        if (Auth::check()) {
            return Auth::user();
        }

        if (Auth::guard('web_admin')->check()) {
            return Auth::guard('web_admin')->user();
        }

        return null;
    }

    /**
     * Lấy id user hiện tại (dựa trên authUser()).
     *
     * @return int|null
     */
    private function authId(): ?int
    {
        $user = $this->authUser();

        return $user?->id;
    }

    /**
     * Kiểm tra user hiện tại có role admin hay không.
     *
     * @return bool
     */
    private function isAdmin(): bool
    {
        $user = $this->authUser();

        return (bool) ($user && $user->role && $user->role->name === 'admin');
    }

    /**
     * ============================
     * HIỂN THỊ GIAO DIỆN CHAT
     * ============================
     * - Nếu người đăng nhập là admin → hiển thị danh sách người dùng (user) để chọn và trò chuyện.
     * - Nếu người đăng nhập là user → tự động chat với admin đầu tiên trong hệ thống.
     */

    /**
     * Hiển thị giao diện chat cho admin.
     * - Load danh sách bệnh nhân (role patient) để admin chọn hội thoại.
     * - Truyền authId sang view để JS so sánh tin nhắn gửi/nhận.
     *
     * @return \Illuminate\View\View
     */
    public function adminChat()
    {
        $user = $this->authUser();

        if (!$user) {
            abort(401);
        }

        // Lấy danh sách tất cả user (trừ chính admin)
        $users = User::where('id', '!=', $user->id)
            ->whereHas('role', function ($query) {
                $query->where('name', 'patient');
            })
            ->with('role')
            ->get();

        $authId = $user->id;

        return view('admin.chat.admin', compact('users', 'authId'));
    }

    /**
     * Điều hướng trang chat theo role.
     * - Nếu là admin: hiển thị giao diện chat admin.
     * - Nếu là patient/user thường: mặc định chat với admin đầu tiên.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $user = $this->authUser(); // Lấy thông tin người dùng hiện tại

        if (!$user) {
            abort(401);
        }

        // Trường hợp người dùng hiện tại là admin
        if ($user->role && $user->role->name === 'admin') {
            return $this->adminChat();
        }

        // Trường hợp người dùng là user thông thường
        // -> Mặc định chỉ có thể chat với admin
        $admin = User::whereHas('role', function ($query) {
            $query->where('name', 'admin');
        })
            ->with('role')
            ->first();

        // Trả về view chat cho user, truyền id của admin để xác định người nhận
        return view('chat.user', ['receiverId' => $admin->id]);
    }

    /**
     * ============================
     * GỬI TIN NHẮN
     * ============================
     * - Xử lý khi người dùng gửi tin nhắn.
     * - Dữ liệu tin nhắn được lưu vào bảng `messages`.
     * - Có kiểm tra quyền: admin chỉ chat với patient, patient chỉ chat với admin.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendMessage(Request $request)
    {
        $sender = $this->authUser();

        if (!$sender) {
            abort(401);
        }

        // Kiểm tra dữ liệu hợp lệ
        $request->validate([
            'receiver_id' => 'required|exists:users,id', // ID người nhận phải tồn tại
            'message' => 'required|string', // Nội dung tin nhắn là chuỗi ký tự
        ]);

        $receiver = User::with('role')->findOrFail($request->receiver_id);

        // Phân quyền: user chỉ chat với admin; admin chỉ chat với user
        if ($sender->role && $sender->role->name === 'admin') {
            if (!$receiver->role || $receiver->role->name !== 'patient') {
                abort(403);
            }
        } else {
            if (!$receiver->role || $receiver->role->name !== 'admin') {
                abort(403);
            }
        }

        // Lưu tin nhắn vào CSDL
        Message::create([
            'sender_id' => $sender->id, // ID người gửi = user đang đăng nhập
            'receiver_id' => $request->receiver_id, // ID người nhận được truyền từ request
            'message' => $request->message, // Nội dung tin nhắn
        ]);

        // Trả về phản hồi JSON cho AJAX
        return response()->json(['status' => 'success']);
    }

    /**
     * Đếm tổng số tin nhắn chưa đọc gửi tới admin hiện tại.
     * Dùng cho badge thông báo ở sidebar admin.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function adminUnreadCount()
    {
        $user = $this->authUser();

        if (!$user) {
            abort(401);
        }

        if (!$user->role || $user->role->name !== 'admin') {
            abort(403);
        }

        $count = Message::where('receiver_id', $user->id)
            ->where('is_read', false)
            ->count();

        return response()->json(['count' => $count]);
    }

    /**
     * Đếm số tin nhắn chưa đọc theo từng bệnh nhân gửi tới admin.
     * Trả về map: [sender_id => unread_count].
     * Dùng để hiển thị badge số ngay trên từng bệnh nhân trong danh sách chat admin.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function adminUnreadByUser()
    {
        $user = $this->authUser();

        if (!$user) {
            abort(401);
        }

        if (!$user->role || $user->role->name !== 'admin') {
            abort(403);
        }

        $rows = Message::query()
            ->select('sender_id', DB::raw('COUNT(*) as unread_count'))
            ->where('receiver_id', $user->id)
            ->where('is_read', false)
            ->groupBy('sender_id')
            ->get();

        $byUser = [];

        foreach ($rows as $row) {
            $byUser[(int) $row->sender_id] = (int) $row->unread_count;
        }

        return response()->json(['by_user' => $byUser]);
    }

    /**
     * Đếm tin nhắn chưa đọc của user hiện tại từ 1 người gửi cụ thể (senderId).
     * Ví dụ: bệnh nhân đếm tin chưa đọc từ admin.
     *
     * @param  int|string  $senderId
     * @return \Illuminate\Http\JsonResponse
     */
    public function userUnreadCount($senderId)
    {
        $user = $this->authUser();

        if (!$user) {
            abort(401);
        }

        $count = Message::where('sender_id', $senderId)
            ->where('receiver_id', $this->authId())
            ->where('is_read', false)
            ->count();

        return response()->json(['count' => $count]);
    }

    /**
     * ============================
     * LẤY DANH SÁCH TIN NHẮN
     * ============================
     * - Lấy toàn bộ tin nhắn giữa người dùng hiện tại và người được chọn (receiver).
     * - Sắp xếp theo thời gian tăng dần (tin nhắn cũ trước, mới sau).
     * - Khi mở hội thoại sẽ đánh dấu đã đọc (is_read = true) các tin nhắn gửi tới user hiện tại.
     * - Có kiểm tra quyền: admin chỉ chat với patient, patient chỉ chat với admin.
     *
     * @param  int|string  $receiverId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getMessages($receiverId)
    {
        $sender = $this->authUser();

        if (!$sender) {
            abort(401);
        }

        $receiver = User::with('role')->findOrFail($receiverId);

        // Phân quyền: user chỉ chat với admin; admin chỉ chat với user
        if ($sender->role && $sender->role->name === 'admin') {
            if (!$receiver->role || $receiver->role->name !== 'patient') {
                abort(403);
            }
        } else {
            if (!$receiver->role || $receiver->role->name !== 'admin') {
                abort(403);
            }
        }

        // Đánh dấu tin nhắn đã đọc khi mở hội thoại
        Message::where('sender_id', $receiverId)
            ->where('receiver_id', $this->authId())
            ->where('is_read', false)
            ->update(['is_read' => true]);

        // Lấy toàn bộ tin nhắn giữa 2 người
        $messages = Message::where(function ($q) use ($receiverId) {
            // Điều kiện: user hiện tại là người gửi và receiver là người nhận
            $q->where('sender_id', $this->authId())
                ->where('receiver_id', $receiverId);
        })
            ->orWhere(function ($q) use ($receiverId) {
                // Điều kiện ngược lại: receiver gửi và user hiện tại là người nhận
                $q->where('sender_id', $receiverId)
                    ->where('receiver_id', $this->authId());
            })
            ->orderBy('created_at', 'asc') // Sắp xếp theo thứ tự thời gian
            ->get();

        // Trả về danh sách tin nhắn dạng JSON cho frontend
        return response()->json($messages);
    }
}
