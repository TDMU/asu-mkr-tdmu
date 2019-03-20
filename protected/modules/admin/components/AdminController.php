<?php

class AdminController extends Controller
{
    public function filters() {

        return array(
            'accessControl',
        );
    }

    public function accessRules() {

        return array(
            array('allow',
                'actions' => array(
                    'teachers',
                    'doctors',
                    'admin',
                    'adminCreate',
                    'adminUpdate',
                    'adminDelete',
                    'students',
                    'parents',
                    'stGrants',
                    'dGrants',
                    'pGrants',
                    'timeTable',
                    'prntGrants',
                    'journal',
                    'menu',
                    'seo',
					'settings',
                    'settingsPortal',
                    'list',
                    'closeChair',
                    'deleteCloseChair',
                    'createCloseChair',
                    'studentCard',
                    'mail',
                    'rating',
                    'userHistory',
                    'userHistoryExcel',
                    'deleteUserHistory',
                    'enter',
                    'generateUser',
                    'generateUserExcel',
                    'deleteUser',
                    'st165',
                    'security',
                    'connector',
                    'gsuiteInfo',
                    'gsuiteDeleteUser'
                ),
                'expression' => 'Yii::app()->user->isAdmin',
            ),
            array('deny',
                'users' => array('*'),
            ),
        );
    }
}
