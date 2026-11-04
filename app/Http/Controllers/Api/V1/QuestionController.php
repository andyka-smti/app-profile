<?php

namespace App\Http\Controllers\Api\V1;

use App\Enums\QuestionStatus;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\QuestionResource;
use App\Models\Question;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class QuestionController extends Controller
{
    /**
     * Display a listing of answered public questions.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = Question::answered()
            ->with(['topic', 'latestPublishedAnswer.user'])
            ->whereHas('latestPublishedAnswer');

        if ($request->filled('topic')) {
            $query->whereHas('topic', function ($q) use ($request) {
                $q->where('slug', $request->query('topic'))
                    ->orWhere('id', $request->query('topic'));
            });
        }

        if ($request->filled('search')) {
            $search = $request->query('search');
            $query->where(function ($q) use ($search) {
                $q->where('subject', 'like', "%{$search}%")
                    ->orWhere('body', 'like', "%{$search}%");
            });
        }

        $perPage = min((int) $request->query('per_page', 10), 30);

        return QuestionResource::collection($query->latest()->paginate($perPage));
    }

    /**
     * Submit a new question from the public.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'topic_id' => ['nullable', 'exists:question_topics,id'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:30'],
            'subject' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string', 'min:10', 'max:5000'],
        ]);

        $question = Question::create([
            ...$validated,
            'status' => QuestionStatus::Pending,
            'ip_address' => $request->ip(),
        ]);

        return response()->json([
            'message' => 'Pertanyaan Anda berhasil dikirim dan akan segera ditinjau oleh petugas kami.',
            'data' => [
                'id' => $question->id,
                'subject' => $question->subject,
                'status' => $question->status->value,
                'created_at' => $question->created_at->toISOString(),
            ],
        ], 201);
    }
}
