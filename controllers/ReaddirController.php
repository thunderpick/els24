<?php

namespace app\controllers;

use app\models\Filesystem;


class ReaddirController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $data = Filesystem::findAll(['path' => $_SERVER['DOCUMENT_ROOT']]);

        foreach ($data as $k => $v)
            $data[$k] = $v->toArray();

        return $this->render('index', ['data' => $data]);
    }

    public function actionRefresh()
    {
        $data = new \DirectoryIterator(realpath($_SERVER['DOCUMENT_ROOT']));

        Filesystem::deleteAll(['path' => $data->getPath()]);

        foreach ($data as $fileinfo) {

            if ($fileinfo->isDot()) continue;

            $fileSystemItem = new Filesystem([
                'name' => $fileinfo->getFilename(),
                'size' => $fileinfo->getSize(),
                'type' => $fileinfo->isFile() ? sprintf('.%s', $fileinfo->getExtension()) : '',
                'ctime' => (new \DateTime())->setTimestamp($fileinfo->getCTime())->format('Y-m-d H:i:s'),
                'path' => $data->getPath(),
                'is_dir' => $fileinfo->isDir(),
            ]);

            $fileSystemItem->save();
        }
        return $this->redirect('/readdir/index');
    }

}
