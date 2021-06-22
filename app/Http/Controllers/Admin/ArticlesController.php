<?php

namespace App\Http\Controllers\Admin;

use App\Events\Base\CreatedContentEvent;
use App\Events\Base\DeletedContentEvent;
use App\Events\Base\UpdatedContentEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreArticlesRequest;
use App\Models\Article;
use App\Models\Setting;
use App\Repositories\Article\Interfaces\ArticleInterface;
use App\Repositories\Eloquent\ArticleEloquentResponsitory;
use App\Services\Media\ThumbnailService;
use App\Services\Media\UploadsManager;
use Event;
use File;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Image;
use RvMedia;
class ArticlesController extends Controller
{
    /**
     * @var ArticleEloquentResponsitory
     */
    protected $articleRepository;
    protected $uploadManager;

    /**
     * ArticlesController constructor.
     * @param ArticleInterface $articleRepository
     */
    public function __construct(ArticleInterface $articleRepository, UploadsManager $uploadManager, ThumbnailService $thumbnailService)
    {
        $this->thumbnailService = $thumbnailService;
        $this->articleRepository = $articleRepository;
        $this->uploadManager = $uploadManager;
        if (!session_id()) {
            session_start();
            $kcfinderSession = [
                'disabled'  => false,
                'uploadURL' => url('upload/'),
                'uploadDir' => ""
            ];
            $_SESSION['KCFINDER'] = $kcfinderSession;
        }
    }

    public function index()
    {
        $articles = $this->articleRepository->all();
        return view('admin.articles.index', compact('articles'));
    }

    public function create()
    {
        $article = new Article;
        return view('admin.articles.create', compact('article'));
    }

    /**
     * @param StoreArticlesRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreArticlesRequest $request)
    {
        $data = $request->all();
        $article = $this->articleRepository->createOrUpdate($data);
        if ($request->hasFile('image')) {
            $upload = $request->file('image');
            if (!$upload->isValid()) {
                return redirect()->back()->withErrors(['Việc upload ảnh đại diện thất bại, vui lòng thử lại sau.'])->withInput();
            }
            $ext = $upload->getClientOriginalExtension();
            $file_name   = Str::slug($request->input('title_alias')) . '.' . $ext;
            $path_upload = public_path('upload/' . $article->getImageFolder() . '/' . $article->id);
            if (!is_dir($path_upload)) {
                File::makeDirectory($path_upload, 755,true);
            }
            $path_folder = $path_upload . '/' . $file_name;
            $img = Image::make($upload);
            $watermark = Image::make(public_path('upload/settings/' . Setting::getSetting('site')->watermark))->widen(floor($img->width() / 3), function ($constraint) {
                $constraint->upsize();
            });
            $img->insert($watermark, 'bottom-right', floor($img->width() / 100), floor($img->width() / 100))->save($path_folder);
            $article->image = $file_name;
            $article->save();
            if (\File::exists($path_folder)) {
                if (is_image($this->uploadManager->fileMimeType($path_folder))) {
                    foreach (config('media.sizes') as $size) {
                        $readable_size = explode('x', $size);
                        $this->thumbnailService
                            ->setImage($upload->getRealPath())
                            ->setSize($readable_size[0], $readable_size[1])
                            ->setDestinationPath($article->getImageFolder() . '/' . $article->id)
                            ->setFileName(\File::name($file_name) . '-' . $size . '.' . $ext)
                            ->save();
                    }
                }
            }
        }
        // End upload file

        event(new CreatedContentEvent(get_class($this->articleRepository->getModel()), $request, $article));
        // Add tagging for article
        if ($request->get('hash_tags')) {
            $article->getModel()->tag($request->get('hash_tags'));
        }
        if ($request->get('submit') == 'save') {
            return redirect()->route('admin::articles.index')->with('status', trans('notices.create_success_message'));
        } else {
            return redirect()->route('admin::articles.edit', $article->id)->with('status', trans('notices.create_success_message'));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($id)
    {
        $article = $this->articleRepository->findById($id);
        return view('admin.articles.show', compact('article'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function edit($id)
    {
        $article = Article::with('tagged')->find($id);
        Event::dispatch('article.editing', [$article]);
        if (!$article)
            return redirect()->route('admin::articles.index')->with('errors', 'Không tồn tại bài viết !');
        return view('admin.articles.edit', compact('article', 'tags'));
    }

    /**
     * Update a article.
     *
     * @param  StoreArticlesRequest $request
     * @param  int $id
     * @return Response
     */
    public function update(StoreArticlesRequest $request, $id)
    {
        $article = $this->articleRepository->findById($id);
        $article->fill($request->input());
        $this->articleRepository->createOrUpdate($article);
        if ($request->hasFile('image')) {
            $upload = $request->file('image');
            if (!$upload->isValid()) {
                return redirect()->back()->withErrors(['Việc upload ảnh đại diện thất bại, vui lòng thử lại sau.'])->withInput();
            }
            $ext = $upload->getClientOriginalExtension();
            $file_name   = Str::slug($request->input('title_alias')) . '.' . $ext;
            $folder = '/upload/' . $article->getImageFolder() . '/' . $article->id;
            $path_upload = public_path($folder);
            if (!is_dir($path_upload)) {
                File::makeDirectory($path_upload, 755,true);
            }
            $img = Image::make($upload);
            $watermark = Image::make(public_path('upload/settings/' . Setting::getSetting('site')->watermark))->widen(floor($img->width() / 3), function ($constraint) {
                $constraint->upsize();
            });
            $path_folder = $path_upload . '/' . $file_name;
            $img->insert($watermark, 'bottom-right', floor($img->width() / 100), floor($img->width() / 100))->save($path_folder);

            $article->image = $folder . '/' . $file_name;
            $article->save();
            if (\File::exists($path_folder)) {
                if (is_image($this->uploadManager->fileMimeType($path_folder))) {
                    foreach (config('media.sizes') as $size) {
                        $readable_size = explode('x', $size);
                        $this->thumbnailService
                            ->setImage($upload->getRealPath())
                            ->setSize($readable_size[0], $readable_size[1])
                            ->setDestinationPath($article->getImageFolder() . '/' . $article->id)
                            ->setFileName(\File::name($file_name) . '-' . $size . '.' . $ext)
                            ->save();
                    }
                }
            }
        } else {

            if ($article->image && \File::exists(public_path($article->image))) {
                $folder = '/upload/' . $article->getImageFolder() . '/' . $article->id;
                $path_upload = public_path($folder);
                if (!is_dir($path_upload)) {
                    File::makeDirectory($path_upload, 755,true);
                }

                if (strpos($article->image, $article->getImageFolder()) === false) {
                    $file_tmp  = str_replace('/upload/', '', $article->image);
                    $file_name = $folder . '/' . $file_tmp;
                    rename(public_path($article->image), public_path($file_name));
                    $article->image = $file_name;
                    $article->save();

                    if (is_image($this->uploadManager->fileMimeType($article->image))) {
                        $ext = Image::make($article->image)->extension;

                        foreach (config('media.sizes') as $size) {
                            $readable_size = explode('x', $size);
                            $this->thumbnailService->setImage($article->image)->setSize($readable_size[0],
                                    $readable_size[1])->setDestinationPath($article->getImageFolder() . '/' . $article->id)->setFileName(\File::name($article->image) . '-' . $size . '.' . $ext)->save()
                            ;
                        }
                    }
                }
            }
        }
        event(new UpdatedContentEvent(get_class($this->articleRepository->getModel()), $request, $article));
        // Article tag
        if ($request->has('hash_tags'))
            $article->getModel()->retag($request->get('hash_tags'));
        else
            $article->getModel()->untag();

        if ($request->get('submit') == 'save') {
            return redirect()->route('admin::articles.index')->with('status', trans('notices.update_success_message'));
        } else {
            return redirect()->route('admin::articles.edit', $id)->with('status', trans('notices.update_success_message'));
        }
    }

    /**
     * Remove a Article
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy(Request $request, $id)
    {
        if ($request->ajax()) {
            $article = $this->articleRepository->getModel()->with('tagged')->find($id);
            $this->articleRepository->delete($article);
            event(new DeletedContentEvent(get_class($this->articleRepository->getModel()), $request, $article));
            return response()->json([
                'msg'    => trans('notices.delete_success_message'),
                'status' => 200
            ], 200);
        }
        return redirect()->route('admin::articles.index')->with('error', 'Đã gặp phải lỗi trong quá trình thao tác, vui lòng thử lại sau.');
    }
}
