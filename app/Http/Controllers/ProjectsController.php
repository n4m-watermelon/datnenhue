<?php

namespace App\Http\Controllers;

use App\Http\Requests\SendMailProjectRequest;
use App\Models\Project;

class ProjectsController extends Controller
{
    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($id)
    {
        $project = Project::findOrFail($id);
        $project->timestamps = false;
        $project->hits++;
        $project->update();
        /** get same article */
        $same_projects = Project::whereRaw('public = 1 and id != ' . $project->id . ' and category_id = ' . $project->category_id)
            ->orderBy('id', 'desc')
            ->take(10)
            ->get();
        /** get tag by article*/
        return view('frontend.projects.show', compact('project', 'same_projects'));
    }

    /**
     *
     * Send Mail Project
     *
     * @param SendMailProjectRequest $request
     * @param $project_alias
     * @return \Illuminate\Http\RedirectResponse
     */
    public function send(SendMailProjectRequest $request, $project_alias)
    {
        $project = Project::where('title_alias', '=', $project_alias)->firstOrFail();
        $data = $request->all();
        // Send mail
        Mail::send('emails.projects.contact', $data, function ($message) use ($project) {
            $message->to('', $project->title)->subject('Mail liên hệ gửi từ website ' . SITENAME);
        });
        return redirect()->back()->with('message', 'Cảm ơn quý khách đã liên hệ với chúng tôi. Chúng tôi sẽ hồi âm trong thời gian sớm nhất có thể');
    }
}
