<?php
    function createTable($gr1,$li, $year, $sem)
    {
        $pattern='<div class="group-box">
                    <div class="group-title">%s</div>
                    <div class="group-students">
                        <ul>
                           %s
                        <ul>
                    </div>
                </div>';
        $gr=Gr::model()->findByPk($gr1);
        if(!empty($gr))
        {
            $course = Gr::model()->getCourseFor($gr->gr1, $year, $sem);
            $name   = Gr::model()->getGroupName($course, $gr);
            return sprintf($pattern,$name,$li);
        }else
            return '';
    }

    $students = St::model()->getStudentsOfNr($nr/*, $year, $sem*/);

    $gr1=-1;
    $li='';
    $all='';

    foreach($students as $student):
            if($gr1!=$student['gr1'])
            {
                if($gr1!=-1)
                {
                    $all.=createTable($gr1,$li,$year,$sem);
                }
                $li='';
                $gr1=$student['gr1'];
            }
            $li.='<li>'.$student['stud'].'</li>';
     endforeach;
    $all.=createTable($gr1,$li,$year,$sem);
    echo $all;
