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
            $reports = $user->report()->with(['counselor', 'user'])->orderBy('date')->get();
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
     * 
     */
    #[Group('Report')]
    public function confirm(ConfirmReportRequest $request, Report $report)
    {
        $report->update($request->all());
        return $this->created(new ReportResource($report));
    }

    /**
     * Reschedule report meeting
     */
    #[Group('Report')]
    public function reschedule(ConfirmReportRequest $request, Report $report)
    {
        $data = $request->all();
        $data["status"] = "dijadwalkan";
        $report->update($data);
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

    /**
     * Get waiting report count
     */
    #[Group('Dashboard')]
    public function getReportCount()
    {
        Gate::authorize('dashboard-data');
        $count = Report::whereStatus('menunggu')->whereIn('user_id', Auth::user()->counselored->pluck('id'))->count();
        return $this->success(["count" => (int) $count]);
    }
    /**
     * Get scheduled report count today
     */
    #[Group('Dashboard')]
    public function getScheduleCount()
    {
        Gate::authorize('dashboard-data');
        $count = Report::where('date', now()->toDateString())->whereStatus('disetujui')->whereIn('user_id', Auth::user()->counselored->pluck('id'))->count();
        return $this->success(["count" => (int) $count]);
    }
    /**
     * Get report and sharing count graph
     * @response array{
     *   data: array{
     *     report: array<string, int>,
     *     sharing: array<string, int>
     *   }
     * }
     */
    #[Group('Dashboard')]
    public function getReportGraph()
    {
        Gate::authorize('dashboard-data');
        $report = Report::whereIn('user_id', Auth::user()->counselored->pluck('id'))
            ->selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, COUNT(*) as total')
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month');

        $sharing = Sharing::whereIn('user_id', Auth::user()->counselored->pluck('id'))
            ->selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, COUNT(*) as total')
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month');

        return $this->success([
            "report"  => (object) $report,
            "sharing" => (object) $sharing,
        ]);
    }
}
