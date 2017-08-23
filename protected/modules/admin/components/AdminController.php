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
                    'admin',
                    'adminCreate',
                    'adminUpdate',
                    'adminDelete',
                    'students',
                    'parents',
                    'stGrants',
                    'pGrants',
                    'timeTable',
                    'prntGrants',
                    'journal',
                    'modules',
                    'entrance',
                    'menu',
                    'employment',
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
                    'connector'
                ),
                'expression' => 'Yii::app()->user->isAdmin',
            ),
            array('deny',
                'users' => array('*'),
            ),
        );
    }
}
