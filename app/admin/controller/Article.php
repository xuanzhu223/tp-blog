<?php
namespace app\admin\controller;

use think\Request;
use app\util\Token;
use app\admin\model\Article as ArticleModel;
use app\admin\validate\ArticleValidate;

class Article
{
   protected $request;
   protected $param;
    /**
     * 构造方法
     * @param Request $request Request对象
     * @access public
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->header = $request->header();
        $this->param = $this->request->param();
    }

    public function initArticle()
    {
        try {
            $userId = $this->request->userId;
            list($dealRet, $response) = (new ArticleModel)->initArticle($userId);
            if (!$dealRet) {
                return ajaxReturn(ERR_CODE_INIT_ARTICLE, $response);
            }
        } catch (\Exception $e) {
            return ajaxReturn(ERR_CODE_INIT_ARTICLE,$e->getMessage());
        }
        return ajaxReturn(SUCCESS,'',['id'=>$response]);
    }


    public function getArticleList()
    {
        try {
            $userId = $this->request->userId;
            list($dealRet, $response) = (new ArticleModel)->getArticleList($userId, $this->param);
            if (!$dealRet) {
                return ajaxReturn(ERR_CODE_GET_ARTICLE, $response);
            }
        } catch (\Exception $e) {
            return ajaxReturn(ERR_CODE_GET_ARTICLE,$e->getMessage());
        }
        return ajaxReturn(SUCCESS,'',$response);
    }

    public function delArticle()
    {
        try {
            $userId = $this->request->userId;
            list($dealRet, $articleList) = (new ArticleModel)->delArticle($this->request->param('id'));
            if (!$dealRet) {
                return ajaxReturn(ERR_CODE_DEL_ARTICLE, $articleList);
            }
        } catch (\Exception $e) {
            return ajaxReturn(ERR_CODE_DEL_ARTICLE, $e->getMessage());
        }
        return ajaxReturn(SUCCESS,'',$articleList);
    }

    public function getArticleDetail()
    {
        try {
            list($dealRet, $response) = (new ArticleModel)->getArticleDetail(intval($this->request->param('id')));
            if (!$dealRet) {
                return ajaxReturn(ERR_CODE_GET_ARTICLE_DETAIL, $response);
            }
        } catch (\Exception $e) {
            return ajaxReturn(ERR_CODE_GET_ARTICLE_DETAIL, $e->getMessage());
        }
        return ajaxReturn(SUCCESS,'',$response);
    }

    public function editArticle()
    {
        try {
            validate(ArticleValidate::class)->scene('edit')->check($this->param);
            $param = $this->param;
            $param['draft'] = htmlspecialchars_decode($param['draft']);
            list($dealRet, $response) = (new ArticleModel)->editArticle($this->request->param('id'), $param);
            if (!$dealRet) {
                return ajaxReturn(ERR_CODE_SAVE_ARTICLE, $response);
            }
        } catch (\ValidateException $e) {
            return ajaxReturn(ERR_CODE_SAVE_ARTICLE, $e->getMessage());
        } catch (\Exception $e) {
            return ajaxReturn(ERR_CODE_SAVE_ARTICLE,'111'.$e->getMessage());
        }
        return ajaxReturn(SUCCESS);
    }
}
