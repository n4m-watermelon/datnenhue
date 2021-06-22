<?php

namespace App\Http\Controllers\Admin;

use App\Events\Base\DeletedContentEvent;
use App\Http\Controllers\Controller;
use App\Repositories\AuditHistory\Interfaces\AuditHistoryInterface;
use Illuminate\Http\Request;

class AuditHistoryController extends Controller
{
    /**
     * @var AuditHistoryInterface
     */
    protected $auditLogRepository;

    /**
     * AuditHistoryController constructor.
     * @param AuditHistoryInterface $auditLogRepository
     */
    public function __construct(AuditHistoryInterface $auditLogRepository)
    {
        $this->auditLogRepository = $auditLogRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $limit = request()->input('paginate', 20);
        $histories = $this->auditLogRepository
            ->getModel()
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->paginate($limit);

        return view('admin.audit-log.index', compact('histories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request, $id)
    {
        try {
            $log = $this->auditLogRepository->findById($id);
            $this->auditLogRepository->delete($log);
            event(new DeletedContentEvent(get_class($this->auditLogRepository->getModel()), $request, $log));

            if ($request->ajax()) {
                return response()->json([
                    'msg' => trans('notices.delete_success_message'),
                    'status' => 200
                ], 200);
            }
            return redirect()->route('admin::audit-logs.index')->with('status', trans('notices.delete_success_message'));
        } catch (\Exception $ex) {
            return response()->json([
                'msg' => $ex->getMessage(),
                'status' => $ex->getCode()
            ], $ex->getCode());
        }
    }
}
