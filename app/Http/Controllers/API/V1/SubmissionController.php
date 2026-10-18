<?php

namespace App\Http\Controllers\Api\V1;

use App\Enums\SubmissionStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCompetitionRequest;
use App\Models\Submission;
use App\Models\SubmissionMember;
use App\Models\Evidence;
use App\Models\ScoringConfig;
use App\Services\Scoring\ScoringCalculator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SubmissionController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->attributes->get('currentUser');

        $submissions = Submission::query()
            ->where('submitted_by', $user->id)
            ->latest()
            ->paginate(20);

        return response()->json([
            'data' => $submissions->items(),
            'meta' => [
                'page' => $submissions->currentPage(),
                'limit' => $submissions->perPage(),
                'total' => $submissions->total(),
            ],
        ]);
    }

    public function storeCompetition(
        StoreCompetitionRequest $request,
        ScoringCalculator $calculator
    ) {
        $user = $request->attributes->get('currentUser');
        $data = $request->validated();

        if (!in_array($user->id, $data['member_ids'], true)) {
            return response()->json([
                'error' => [
                    'code' => 'VALIDATION_ERROR',
                    'message' => 'Pengaju harus termasuk anggota tim.',
                ],
            ], 422);
        }

        if (count($data['member_ids']) !== $data['team_size']) {
            return response()->json([
                'error' => [
                    'code' => 'VALIDATION_ERROR',
                    'message' => 'Jumlah anggota tim tidak sesuai.',
                ],
            ], 422);
        }

        $config = ScoringConfig::active()->config;

        $estimate = $calculator->competition(
            $config,
            $data['achievement'],
            $data['level'],
            $data['team_size']
        );

        $submission = DB::transaction(function () use (
            $data,
            $user,
            $estimate
        ) {
            $submission = Submission::create([
                'type' => 'competition',
                'status' => SubmissionStatus::Pending->value,
                'submitted_by' => $user->id,
                'title' => $data['title'],
                'description' => $data['competition_name'],
                'achieved_on' => $data['achieved_on'],
            ]);

            foreach ($data['member_ids'] as $memberId) {
                SubmissionMember::create([
                    'submission_id' => $submission->id,
                    'user_id' => $memberId,
                    'contribution' => 'core',
                ]);
            }

            foreach ($data['evidence'] as $item) {
                Evidence::create([
                    'submission_id' => $submission->id,
                    'kind' => $item['kind'],
                    'url' => $item['url'] ?? null,
                    'file_key' => $item['file_key'] ?? null,
                ]);
            }

            \App\Models\CompetitionDetail::create([
                'submission_id' => $submission->id,
                'competition_name' => $data['competition_name'],
                'organizer' => $data['organizer'] ?? null,
                'competition_url' => $data['competition_url'] ?? null,
                'achievement' => $data['achievement'],
                'level' => $data['level'],
                'team_size' => $data['team_size'],
            ]);

            return $submission;
        });

        return response()->json([
            'data' => [
                'id' => $submission->id,
                'type' => $submission->type,
                'status' => $submission->status,
                'estimated_points_per_member' => $estimate,
                'created_at' => $submission->created_at,
            ],
        ], 201);
    }
}