<?php

namespace App\Http\Controllers;

use App\Http\Requests\CloseReportRequest;
use App\Http\Requests\ConfirmReportRequest;
use App\Http\Requests\CreateReportRequest;
use App\Http\Resources\ReportResource;
use App\Models\Report;
use App\Models\Sharing;
use App\Traits\ApiResponder;
use Dedoc\Scramble\Attributes\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class ReportController extends Controller
{
    use ApiResponder;

    /**
     * Get all report data
     * 
     * Get latest reports data belongs to student for student or belongs to all student of counselored for counselor
     */
    #[Group('Report')]
    public function index()
    {
        $user = Auth::user();
        if ($user->role == 'student') {
            $reports = $user->report;
        } else if ($user->role == 'counselor') {
            $reports = Report::with(['user', 'user.room'])->whereIn('user_id', $user->counselored->pluck('id'))->get();
        }
        return $this->success(ReportResource::collection($reports));
    }

    /**
     * Create new report
     */
    #[Group('Report')]
    public function store(CreateReportRequest $request)
    {
        $report = Report::create($request->all());
        Sharing::where('user_id', Auth::id())->where('created_at', 'like', now()->toDateString() . '%')->update(["priority" => "tinggi"]);
        return $this->created(new ReportResource($report));
    }

    /**
     * Get report detail
     */
    #[Group('Report')]
    public function view(Report $report)
    {
        Gate::authorize('view', $report);
        return $this->created(new ReportResource($report));
    }

    /**
     * Confirm report meeting
     */
    #[Group('Report')]
    public function confirm(ConfirmReportRequest $request, Report $report)
    {
        $report->update($request->all());
        return $this->created(new ReportResource($report));
    }

    /**
     * Finish report meeting
     */
    #[Group('Report')]
    public function close(CloseReportRequest $request, Report $report)
    {
        $report->update(["result" => $request->result, "status" => "selesai"]);
        return $this->created(new ReportResource($report));
    }

    /**
     * Cancel report meeting
     */
    #[Group('Report')]
    public function cancel(CloseReportRequest $request, Report $report)
    {
        $report->update(["result" => $request->result, "status" => "dibatalkan"]);
        return $this->created(new ReportResource($report));
    }
}
