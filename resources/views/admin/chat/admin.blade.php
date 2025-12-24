@extends('layouts.admin')

@section('title', 'Chat với bệnh nhân')

@section('content')
    {{-- Chat CSS --}}
    <link rel="stylesheet" href="{{ asset('css/chat/chat_admin.css') }}">

    {{-- Chat Box --}}
    <div id="admin-chat-box" class="chat-box shadow">
        {{-- Chat Header --}}
        <div class="chat-header bg-primary text-white d-flex justify-content-between align-items-center p-2">
            <span>Chat với bệnh nhân</span>
        </div>

        <div class="row g-0">
            {{-- Left Side: User List --}}
            <div class="col-4 border-end bg-light chat-users">
                <ul class="list-group list-group-flush" id="user-list">
                    @foreach($users as $user)
                        <li class="list-group-item user-item" data-id="{{ $user->id }}">
                            <div class="d-flex align-items-center">
                                <span class="flex-grow-1">{{ $user->name }}</span>
                                <span class="badge bg-danger rounded-pill ms-2 d-none unread-badge" data-user-id="{{ $user->id }}"></span>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>

            {{-- Right Side: Chat Content --}}
            <div class="col-8 d-flex flex-column chat-content">
                {{-- Chat Messages --}}
                <div id="chat-messages" class="chat-messages bg-white">
                    <p class="text-muted text-center mt-4">
                        Chọn bệnh nhân để chat
                    </p>
                </div>

                {{-- Chat Input Form --}}
                <form id="chat-form" class="chat-input d-none">
                    @csrf
                    <input type="hidden" id="receiver_id">
                    <input type="text"
                           id="message"
                           class="form-control me-2"
                           placeholder="Nhập tin nhắn...">
                    <button class="btn btn-primary">Gửi</button>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        window.ChatAdminConfig = {
            authId: {{ $authId }},
            csrfToken: "{{ csrf_token() }}",
            messagesUrlTemplate: "{{ route('admin.chat.messages', ['receiverId' => '__RID__']) }}",
            unreadByUserUrl: "{{ route('admin.chat.unread_by_user') }}",
            sendUrl: "{{ route('admin.chat.send') }}"
        };
    </script>
    <script src="{{ asset('js/chat/chat_admin.js') }}"></script>
@endpush
