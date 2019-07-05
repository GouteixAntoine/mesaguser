<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMessage;
use App\Notifications\MessageReceived;
use App\Repository\ConversationRepository;
use App\User;
use Illuminate\Auth\AuthManager;

class ConversationsController extends Controller
{
    /**
     * @var ConversationRepository
     */
    private $repo;
    /**
     * @var AuthManager
     */
    private $auth;

    public function __construct(ConversationRepository $conversationRepository, AuthManager $auth)
    {
        $this->middleware('auth');
        $this->repo = $conversationRepository;
        $this->auth = $auth;
    }
    public function index() {
        return view('conversations/index', [
            'users' => $this->repo->getConversation($this->auth->user()->id),
            'unread' => $this->repo->unreadCount($this->auth->user()->id)
        ]);
    }

    public function show (User $user) {
        $messages = $this->repo->getMessageFor($this->auth->user()->id, $user->id)->paginate(50);
        $unread = $this->repo->unreadCount($this->auth->user()->id);
        if(isset($unread[$user->id])) {
            $this->repo->readAllFrom($user->id, $this->auth->user()->id);
            unset($unread[$user->id]);
        }
        return view('conversations/show', [
            'users' => $this->repo->getConversation($this->auth->user()->id),
            'user' => $user,
            'messages' => $messages,
            'unread' => $unread
        ]);
    }

    public function store (User $user, StoreMessage $request){
        $message = $this->repo->createMessage(
            $request->get('content'),
            $this->auth->user()->id,
            $user->id
        );
        $user->notify(new MessageReceived($message));
        return redirect(route('conversations.show', ['id' => $user->id]));
    }
}
