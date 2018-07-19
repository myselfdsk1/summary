<?php

class OperatorController extends Controller
{
	/**
	 * Declares class-based actions.
	 */

    public function getSubMenu() {
      $subMenuItems=array();
      /*if (!Yii::app()->user->isGuest) {
              $subMenuItems[] = array('Список абитуриентов','operator/listAbiturient');
              $subMenuItems[] = array('Item2','');
        }*/
      return $subMenuItems;
    }

     public function getHint() {
        $action_text = Yii::app()->controller->action->id;
        $text_hint = '';
        if ($action_text == 'listAbiturient') {
            $text_hint = 'Просмотр списка всех абитуриентов';    
        }
        return $text_hint;
     }

	public function actions()
	{
		return array(
			// captcha action renders the CAPTCHA image displayed on the contact page
			'captcha'=>array(
				'class'=>'CCaptchaAction',
				'backColor'=>0xFFFFFF,
			),
			// page action renders "static" pages stored under 'protected/views/site/pages'
			// They can be accessed via: index.php?r=site/page&view=FileName
			'page'=>array(
				'class'=>'CViewAction',
			),
		);
	}

	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex()
	{
        if (!Yii::app()->user->checkAccess('operatorplace')){
            if (Yii::app()->user->isGuest) $this->redirect(array('site/login'));
            else $this->redirect(array('site/index'));
        }
		// renders the view file 'protected/views/site/index.php'
		// using the default layout 'protected/views/layouts/main.php'
       // $this->render('index');
        $this->redirect(array('operator/listAbiturient'));
	}

	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
	    if($error=Yii::app()->errorHandler->error)
	    {
	    	if(Yii::app()->request->isAjaxRequest)
	    		echo $error['message'];
	    	else
	        	$this->render('error', $error);
	    }
	}

    public function actionListAbiturient()
	{

	    if (!Yii::app()->user->checkAccess('operatorplace')){
            if (Yii::app()->user->isGuest) $this->redirect(array('site/login'));
            else $this->redirect(array('site/index'));
        }

        $users = new UserInfo('search');
        
        if (isset($_GET['UserInfo'])) {
                echo 'wea re here';
                   $users->attributes=$_GET['UserInfo'];

        }
        $this->render('listAbiturient', array('users'=>$users));
	}

    public function actionViewAbiturient() {

        if (!Yii::app()->user->checkAccess('operatorplace')){
            if (Yii::app()->user->isGuest) $this->redirect(array('site/login'));
            else $this->redirect(array('site/index'));
        }

        if (isset($_GET['aid'])) {
            $aid = $_GET['aid'];
            $users = Users::model()->with('userinfo')->find('t.userid=:userid',array(':userid'=>$aid));

            if ($users->userinfo->reg_country){
                $rcountry = Country::model()->find('countryid=:countryid', array(':countryid'=>$users->userinfo->reg_country));
                $reg_country = $rcountry->name;
            }
            else $reg_country = '';

            if ($users->userinfo->reg_region){
                $rregion = Region::model()->find('regionid=:regionid', array(':regionid'=>$users->userinfo->reg_region));
                if (!(empty($rregion->fnrec))) {
                    $rregion1 = ClRegion::model()->find("fnrec=:fnrec", array(':fnrec'=>$rregion->fnrec));
                    if (isset($rregion1->name)) {
                        $reg_region = $rregion1->name;
                    } else {$reg_region = '';}
                } else {
                    $reg_region = $rregion->name;
                }
            }
            else $reg_region = '';

            if ($users->userinfo->reg_city){
                $rcity = City::model()->find('cityid=:id', array(':id'=>$users->userinfo->reg_city));
                if (!(empty($rcity->fnrec))) {
                    $rcity1 = ClCity::model()->find("fnrec=:fnrec", array(':fnrec'=>$rcity->fnrec));
                    if (isset($rcity1->name)) {
                        $reg_city = $rcity1->name;
                    } else {$reg_city = '';}

                } else {
                    $reg_city = $rcity->name;
                }
            }
            else $reg_city = '';

            if ($users->userinfo->reg_street){
                $rstreet = Street::model()->find('streetid=:id', array(':id'=>$users->userinfo->reg_street));
                if (!(empty($rstreet->fnrec))) {
                    $rstreet1 = ClStreet::model()->find("fnrec=:fnrec", array(':fnrec'=>$rstreet->fnrec));
                    if (isset($rstreet1->name)) {
                        $reg_street = $rstreet1->name;
                    } else {$reg_street = '';}

                } else {
                    $reg_street = $rstreet->name;
                }
            }
            else $reg_street = '';

            $rs_country = '';
            $rs_region = '';
            $rs_city = '';
            $rs_street = '';
            if (($users->userinfo->reside_by_reg == 0) || (empty($users->userinfo->reside_by_reg))) {

                $rscountry = Country::model()->find('countryid=:countryid', array(':countryid'=>$users->userinfo->residence_country));
                if ($rscountry) $rs_country = $rscountry->name;

                $rsregion = Region::model()->find('regionid=:regionid', array(':regionid'=>$users->userinfo->residence_region));
                if ($rsregion){
                    if (!(empty($rsregion->fnrec))) {
                        $rsregion1 = ClRegion::model()->find("fnrec=:fnrec", array(':fnrec'=>$rsregion->fnrec));
                        if (isset($rsregion1->name)) {
                            $rs_region = $rsregion1->name;
                        } else {$rs_region = '';}
                    } else {
                        $rs_region = $rsregion->name;
                    }
                }

                $rscity = City::model()->find('cityid=:id', array(':id'=>$users->userinfo->residence_city));
                if ($rscity){
                    if (!(empty($rscity->fnrec))) {
                        $rscity1 = ClCity::model()->find("fnrec=:fnrec", array(':fnrec'=>$rscity->fnrec));
                        if (isset($rscity1->name)) {
                            $rs_city = $rscity1->name;
                        } else {$rs_city = '';}
                    } else {
                        $rs_city = $rscity->name;
                    }
                }

                $rsstreet = Street::model()->find('streetid=:id', array(':id'=>$users->userinfo->residence_street));
                if ($rsstreet){
                    if (!(empty($rstreet->fnrec))) {
                        $rstreet1 = ClStreet::model()->find("fnrec=:fnrec", array(':fnrec'=>$rsstreet->fnrec));
                        if (isset($rstreet1->name)) {
                            $rs_street = $rstreet1->name;
                        } else {$rs_street = '';}

                    } else {
                        $rs_street = $rstreet->name;
                    }
                }
            }

            $school = School::model()->find('schoolid=:id', array(':id'=>$users->userinfo->school));
			if (isset($school->name)) {
				$school_n = $school->name;
			}
			else $school_n = '';
            $_citizen = Citizenship::model()->find('fnrec=:fnrec',array(':fnrec'=>$users->userinfo->citizenship));
            if (isset($_citizen->name)) {
                $citizen = $_citizen->name;
            } else {$citizen = '';}

            $_passport = Passport::model()->find('fnrec=:id', array(':id'=>$users->userinfo->pass_type));
            if (isset($_passport->name)) {
                $passport = $_passport->name;
            } else {$passport = '';}

            $_edu_type = EducationType::model()->find('fnrec=:id',array(':id'=>$users->userinfo->edu_type));
            if (isset($_edu_type->fname)) {
                $edu_type = $_edu_type->fname;
            } else {$edu_type = '';}

            if ($users->userinfo->edu_country){
                $_edu_country = Country::model()->find('countryid=:id', array(':id'=>$users->userinfo->edu_country));
                $edu_country = $_edu_country->name;
            }
            else $edu_country = '';

            if ($users->userinfo->edu_region){
                $_edu_region = Region::model()->find('regionid=:id', array(':id'=>$users->userinfo->edu_region));
                if (!(empty($_edu_region->fnrec))) {
                    $_edu_region1 = ClRegion::model()->find("fnrec=:fnrec", array(':fnrec'=>$_edu_region->fnrec));
                    if (isset($_edu_region1->name)) {
                        $edu_region = $_edu_region1->name;
                    } else {$edu_region = '';}
                } else {
                    $edu_region = $_edu_region->name;

                }
            }
            else $edu_region = '';

            if ($users->userinfo->edu_city){
                $_edu_city = City::model()->find('cityid=:id', array(':id'=>$users->userinfo->edu_city));
                if (!(empty($_edu_city->fnrec))) {
                    $_edu_city1 = ClCity::model()->find("fnrec=:fnrec", array(':fnrec'=>$_edu_city->fnrec));
                    if (isset($_edu_city1->name)) {
                        $edu_city = $_edu_city1->name;
                    } else {$edu_city = '';}
                } else {
                    $edu_city = $_edu_city->name;

                }
            }
            else $edu_city = '';

            $_fl = ForeignLanguage::model()->find('fnrec=:id',array(':id'=>$users->userinfo->foreign_language));
            if (isset($_fl->fname)) {
                $fl = $_fl->fname;
            } else {$fl = '';}

            $_sport = Sport::model()->find('fnrec=:id',array(':id'=>$users->userinfo->sport));
            if (isset($_sport->fname)) {
                $sport = $_sport->fname;
            } else {$sport = '';}

            $_pc = PreparatoryCourses::model()->find('fnrec=:id',array(':id'=>$users->userinfo->pre_courses));
            if (isset($_pc->fname)) {
                $pc = $_pc->fname;
            } else {$pc = '';}

            $m = array('0'=>'нет','1'=>'золотая или серебрянная медаль',
                     '2'=>'диплом с отличием техникума(колледжа)','3'=>'диплом с отличием о начальном профобразовании');
            $medal = $m[$users->userinfo->medal];

            $users_olympic = UsersOlympic::model()->findAll('userid=:userid', array(':userid'=>$users->userid));
            $u_olympic = array();
            foreach ($users_olympic as $uo) {
                $od = $uo['olympic_disc'];
                $ot = $uo['olympic_type'];
                $o_type = OlympicType::model()->find('fnrec=:id',array(':id'=>$ot));
                if (isset($o_type->fname)) {
                    $uo_type = $o_type->fname;
                }
                else {$uo_type = '';}
                $o_disc = OlympicDiscipline::model()->find('fnrec=:id',array(':id'=>$od));
                if (isset($o_disc->fname)) {
                    $uo_disc = $o_disc->fname;
                }
                else {$uo_disc = '';}

                $u_olympic[] = array($uo_type, $uo_disc, $uo['place']);
            }

            $users_exam = UsersExam::model()->findAll('userid=:userid', array(':userid'=>$users->userid));
            $u_exam = array();
            foreach ($users_exam as $ue) {
                $od = $ue['discipline'];

                $o_disc = OlympicDiscipline::model()->find('fnrec=:id',array(':id'=>$od));
                if (isset($o_disc->fname)) {
                    $ue_disc = $o_disc->fname;
                }
                else {$ue_disc = '';}
                $u_exam[] = array($ue_disc, $ue['mark']);
            }

            $u_benefit = array();
            $benefits = UsersBenefit::model()->findAll('userid=:userid', array(':userid'=>$users->userid));
            foreach ($benefits as $b) {
                $u_b = Benefit::model()->find('fnrec=:id',array(':id'=>$b['benefitid']));
                if (isset($u_b->name)) {
                    $ub = $u_b->name;
                    $u_benefit[] = $ub;
                }
                else {$ub= '';}
            }
            if (count($u_benefit) > 0) $uncheck_benefits = true;
            else $uncheck_benefits = false;

            $u_plan = array();
            $plans = UsersPlan::model()->findAll('userid=:userid', array(':userid'=>$users->userid));
            foreach ($plans as $p){
                $plan = Plan::model()->findByPK($p->planid);
                if ($p->benefits > 0) $uncheck_benefits = false;
                $u_plan[] = array('plan_id'=>$p->planid, 'finsource'=>$plan->finsource,
                    'spec_code'=>$plan->spec_code, 'spec_name'=>$plan->spec_name, 'priority'=>$p->priority,  'benefits'=>$p->benefits);
            }

            $this->render('viewAbiturient',
                          array('users'=>$users,
                          'reg_country'=>$reg_country,'rs_country'=>$rs_country,
                          'reg_region'=>$reg_region,'rs_region'=>$rs_region,
                          'reg_city'=>$reg_city,'rs_city'=>$rs_city,
                          'reg_street'=>$reg_street,'rs_street'=>$rs_street,
                          'school'=>$school_n,'citizen'=>$citizen,
                          'passport'=>$passport,'edu_type'=>$edu_type,
                          'edu_country'=>$edu_country, 'edu_region'=>$edu_region, 'edu_city'=>$edu_city,
                          'fl'=>$fl, 'sport'=>$sport,'pc'=>$pc, 'medal'=>$medal,
                          'u_olympic'=>$u_olympic,'u_exam'=>$u_exam, 'u_benefit' =>$u_benefit, 'u_plan'=>$u_plan,
                          'uncheck_benefits'=>$uncheck_benefits
                          ));
        }
        else {
            echo 'not id';
        }
    }

    public function actionInGal() {
        $text = '';
        $error = '';
        if (!(isset($_GET['aid']))) {
            $error = $error.' Вы не можете отправить данные по неизвестному абитуриенту!<br>';
            return 0;
            
        }
        $aid = $_GET['aid'];
        $user = Users::model()->with('userinfo')->find('t.userid=:userid',array(':userid'=>$aid));
        /*if ($user->activated != 10) {
            $error = $error.' Данные данного студента не готовы для отправки в галактику!<br>';
            return 0;
        }*/

        $_city = City::model()->findByPk($user->userinfo->reg_city);
        $_street = Street::model()->find('streetid=:streetid', array(':streetid' => $user->userinfo->reg_street));
        $_school = School::model()->find('schoolid=:schoolid', array(':schoolid' => $user->userinfo->school));

        $rn = (int) $user->userid;
        $fio = iconv('utf8','Windows-1251',$user->userinfo->lastname.' '.$user->userinfo->firstname.' '.$user->userinfo->secondname);
        if ($user->userinfo->sex == 1) {
            $sex = iconv('utf8','Windows-1251','М');
        }
        elseif ($user->userinfo->sex == 2) {
            $sex = iconv('utf8','Windows-1251','Ж');
        }
        else {
            $sex = ' ';
        }
        $borndate = $user->userinfo->birthday;
        $email = $user->email;
        $citizen = $user->userinfo->citizenship;
        $passporttype = $user->userinfo->pass_type;
        if (isset($_POST['passportprizn'])) {
            $passportprizn = 1;
        } else { $passportprizn = 0; }
        $passportser = $user->userinfo->pass_ser;
		if (empty($passportser)) {
			$passportser = ' ';
		}
        $passportnumber = $user->userinfo->pass_nmb;
        $passportwho = iconv('utf8','Windows-1251',$user->userinfo->pass_givenwho);
        $passportdate = $user->userinfo->pass_givendate;
		$passportkod = $user->userinfo->pass_depcode;
		if (empty($passportkod)) {
			$passportkod = ' ';
		}
        $passportdateto = $user->userinfo->pass_todate;
        if ($user->userinfo->reg_addr_invalid > 0) {
            $addressex = 0;
        }
        else {$addressex = 1;}
        if (!(empty($_city->fnrec))) {
            $city = $_city->fnrec;
        }
        else{
            $city = '8000000000000000';
        }


        if (!(empty($_street->fnrec))) {
            $street = $_street->fnrec;
            $cl_street = ClStreet::model()->find('fnrec=:fnrec', array('fnrec'=>$_street->fnrec));
            if (!(empty($cl_street->name))) {
                $streetname = iconv('utf8','Windows-1251',$cl_street->name);
            }
            else {
                $streetname = ' ';
            }
        }
        else{
            $street = '8000000000000000';
            $streetname = iconv('utf8','Windows-1251',$_street->name);
        }

        $house = iconv('utf8','Windows-1251',$user->userinfo->reg_house);
        if (empty($user->userinfo->reg_corps)) {
            $building = ' ';
        }

        else {
            $building = $user->userinfo->reg_corps;
        }
        $flat = $user->userinfo->reg_flat;
		if (empty($flat)){
    		$flat = ' ';
		}
        $ind = $user->userinfo->reg_postindex;
        $phone = $user->userinfo->mobile_phone;
        $education = $user->userinfo->edu_type;
        if (!(empty($_school->fnrec))) {
            $school = $_school->fnrec;
        }
        else{
            $school = '8000000000000000';
        }

        $educationend = (int) $user->userinfo->edu_year;
        $educationtype = $user->userinfo->edu_doc_type;
        if (isset($_POST['educationprizn'])) {
            $educationprizn = 1;
        }else { $educationprizn = 0; }
        $educationser = iconv('utf8','Windows-1251',$user->userinfo->edu_doc_ser);
        $educationnumber = $user->userinfo->edu_doc_num;
        $educationdate = $user->userinfo->edu_doc_date;

        if (isset($_POST['egeprizn'])) {
            $egeprizn = 1;
        } else { $egeprizn = 0; }
        $egeser = $user->userinfo->USE_doc_ser;
        $egenumber = $user->userinfo->USE_doc_num;
        $egeyear = (int) $user->userinfo->USE_doc_year;
        if (empty($egeser) || empty($egenumber) || empty($egeyear)){
            $egeex = 0;
            $egeprizn = 0;
        }
        else $egeex = 1;
        
        if (!(empty($user->userinfo->middle_mark))) {
            $avgmark = (int) $user->userinfo->middle_mark;
        } else {
            $avgmark = 0;
        }
        $medal = (int) $user->userinfo->medal;
        
        if (!(empty($user->userinfo->foreign_language))) {
            $languagetype = $user->userinfo->foreign_language;
        } else {
            $languagetype = '8000000000000000';
        }
        if (!(empty($user->userinfo->sport))) {
            $sporttype = $user->userinfo->sport;
        } else {
            $sporttype = '8000000000000000';
        }

        $campusex = (int) $user->userinfo->hostel_exam;
        $campusest = (int) $user->userinfo->hostel_edu;
        if (!(empty($user->userinfo->pre_courses))) {
            $coursetype = $user->userinfo->pre_courses;
        } else {
            $coursetype= '8000000000000000';
        }
        $nyear=date('Y');


        //$c=OCILogon("gal_work", "gal_work", "192.168.0.217:1521/portal","CL8MSWIN1251");
        $c=OCILogon("gal_work", "gal_work", "192.168.0.215:1521/portal","CL8MSWIN1251");
        $_sql = "
    begin a_p_abit(:rn,:fio,:sex,to_date('".$borndate."', 'YYYY-MM-DD'), :email,:citizen,:passporttype,:passportprizn,:passportser,:passportnumber,:passportwho,to_date('".$passportdate."', 'YYYY-MM-DD'), to_date('".$passportdateto."', 'YYYY-MM-DD'),:passportkod,:addressex,:city,:street,:streetname,:house,:building,:flat,:ind,:phone,:education,:school,:educationend,:educationtype,:educationprizn,:educationser,:educationnumber,to_date('".$educationdate."','YYYY-MM-DD'),:egeex,:egeprizn,:egeser,:egenumber,:egeyear,:avgmark,
    :medal,:languagetype,:sporttype,:campusex,:campusest,:coursetype,:nyear,:nresult,:sexeption,:nrec); end;
    ";
        $s = OCIParse($c, $_sql);
        OCIBindByName($s,":rn",$rn);
        OCIBindByName($s,":fio",$fio);
        OCIBindByName($s,":sex",$sex);
        OCIBindByName($s,":email",$email);
        OCIBindByName($s,":citizen",$citizen);
        OCIBindByName($s,":passporttype",$passporttype);
        OCIBindByName($s,":passportprizn",$passportprizn);
        OCIBindByName($s,":passportser",$passportser);
        OCIBindByName($s,":passportnumber",$passportnumber);
        OCIBindByName($s,":passportwho",$passportwho);
        OCIBindByName($s,":passportkod",$passportkod);
        OCIBindByName($s,":addressex",$addressex);
        OCIBindByName($s,":city",$city);
        OCIBindByName($s,":street",$street);
        OCIBindByName($s,":streetname",$streetname);
        OCIBindByName($s,":house",$house);
        OCIBindByName($s,":building",$building);
        OCIBindByName($s,":flat",$flat);
        OCIBindByName($s,":ind",$ind);
        OCIBindByName($s,":phone",$phone);
        OCIBindByName($s,":education",$education);
        OCIBindByName($s,":school",$school);
        OCIBindByName($s,":educationend",$educationend);
        OCIBindByName($s,":educationtype",$educationtype);
        OCIBindByName($s,":educationprizn",$educationprizn);
        OCIBindByName($s,":educationser",$educationser);
        OCIBindByName($s,":educationnumber",$educationnumber);
        OCIBindByName($s,":egeex",$egeex);
        OCIBindByName($s,":egeprizn",$egeprizn);
        OCIBindByName($s,":egeser",$egeser);
        OCIBindByName($s,":egenumber",$egenumber);
        OCIBindByName($s,":egeyear",$egeyear);
        OCIBindByName($s,":avgmark",$avgmark);
        OCIBindByName($s,":medal",$medal);
        OCIBindByName($s,":languagetype",$languagetype);
        OCIBindByName($s,":sporttype",$sporttype);
        OCIBindByName($s,":campusex",$campusex);
        OCIBindByName($s,":campusest",$campusest);
        OCIBindByName($s,":coursetype",$coursetype);
        OCIBindByName($s,":nyear",$nyear);
        OCIBindByName($s,":nresult",$nresult, 1024);
        OCIBindByName($s,":sexeption",$sexeption,1024);
        OCIBindByName($s,":nrec",$nrec,1024);

		
		$abit_sql = '
		 rn := '.$rn.'<br>
         fio := '.$fio.'<br>
        sex := '.$sex.'<br>
        email := '.$email.'<br>
        citizen := '.$citizen.'<br>
        passporttype := '.$passporttype.'<br>
        passportprizn := '.$passportprizn.'<br>
        passportser := '.$passportser.'<br>
        passportnumber := '.$passportnumber.'<br>
        passportwho := '.$passportwho.'<br>
        passportkod := '.$passportkod.'<br>
        addressex := '.$addressex.'<br>
        city := '.$city.'<br>
        street := '.$street.'<br>
        streetname := '.$streetname.'<br>
        house := '.$house.'<br>
        building := '.$building.'<br>
        flat := '.$flat.'<br>
        ind := '.$ind.'<br>
        phone := '.$phone.'<br>
        education := '.$education.'<br>
        school := '.$school.'<br>
        educationend := '.$educationend.'<br>
        educationtype := '.$educationtype.'<br>
        educationprizn := '.$educationprizn.'<br>
        educationser := '.$educationser.'<br>
        educationnumber := '.$educationnumber.'<br>
        egeex := '.$egeex.'<br>
        egeprizn := '.$egeprizn.'<br>
        egeser := '.$egeser.'<br>
        egenumber := '.$egenumber.'<br>
        egeyear := '.$egeyear.'<br>
        avgmark := '.$avgmark.'<br>
        medal := '.$medal.'<br>
        languagetype := '.$languagetype.'<br>
        sporttype := '.$sporttype.'<br>
        campusex := '.$campusex.'<br>
        campusest := '.$campusest.'<br>
		coursetype := '.$coursetype.'<br>
        nyear := '.$nyear.'<br>

		';

        /*echo "begin a_p_abit('".$rn."','".$fio."','".$sex."',to_date('".$borndate."', 'YYYY-MM-DD'),'".$email."','".$citizen."','".$passporttype."','".$passportprizn."','".$passportser."','".$passportnumber."','".$passportwho."',to_date('".$passportdate."', 'YYYY-MM-DD'),'".$passportkod."','".$addressex."','".$city."','".$street."','".$streetname."','".$house."','".$building."','".$flat."','".$ind."','".$phone."','".$education."','".$school."','".$educationend."','".$educationtype."','".$educationprizn."','".$educationser."','".$educationnumber."',to_date('".$educationdate."','YYYY-MM-DD'),'".$egeex."','".$egeprizn."','".$egeser."','".$egenumber."','".$egeyear."','".$avgmark,
    $medal."','".$languagetype."','".$sporttype."','".$campusex."','".$campusest."','".$coursetype."','".$nyear."'); end;";*/

        OCIExecute($s, OCI_NO_AUTO_COMMIT);

        if ($nresult == 0) {
            $text = $text.' Добавлены личные данные абитуриента<br>';

			$user->fnrec = $nrec;
			$user->save();

            $benefits = UsersBenefit::model()->findAll('userid=:userid', array(':userid'=> $user->userid));
            $error_lgot = 0;
            $slgot = '';
            foreach ($benefits as $benefit){
               $lgot = $benefit->benefitid;
               $sql_lgot = "
                    begin
                    A_P_ABIT_LGOT(:rn, :lgot, :nresult, :sexeption);
                    end;

               ";
                $s_lgot = OCIParse($c, $sql_lgot);
                OCIBindByName($s_lgot,":rn",$rn);
                OCIBindByName($s_lgot,":lgot",$lgot);
                OCIBindByName($s_lgot,":nresult",$nresult_lgot, 1024);
                OCIBindByName($s_lgot,":sexeption",$sexeption_lgot, 1024);
                OCIExecute($s_lgot, OCI_NO_AUTO_COMMIT);
                $error_lgot = $error_lgot + $nresult_lgot;
                $slogt = '<br>'.$sexeption_lgot;

            }

            if ($error_lgot == 0 ) {
                     $text = $text.' Добавлены льготы абитуриента <br>';
                     $error_olympic = 0;
                     $solympic = '';
                     $olympics = UsersOlympic::model()->findAll('userid=:userid', array(':userid'=> $user->userid));
                     foreach ($olympics as $olympic){
                        $olimp = $olympic->olympic_type;
                        $dis = $olympic->olympic_disc;
                        $place = $olympic->place;
                        $sql_olympic = "begin A_P_ABIT_olimp(:rn, :olimp, :dis, 100, :place, :nresult,:sexeption); end;";
                        $s_olympic = OCIParse($c, $sql_olympic);
                        OCIBindByName($s_olympic,":rn",$rn);
                        OCIBindByName($s_olympic,":olimp",$olimp);
                        OCIBindByName($s_olympic,":dis",$dis);
                        OCIBindByName($s_olympic,":place",$place);
                        OCIBindByName($s_olympic,":nresult",$nresult_olympic, 1024);
                        OCIBindByName($s_olympic,":sexeption",$sexeption_olympic, 1024);
                        OCIExecute($s_olympic, OCI_NO_AUTO_COMMIT);
                        $error_olympic = $error_olympic + $nresult_olympic;
                        $solympic = '<br>'.$sexeption_olympic;
                     }

                     if ($error_olympic == 0 ) {
                         $text = $text.' Добавлены олимпиады абитуриента <br>';
                         $error_exam = 0;
                         $sexam = '';
                         $exams = UsersExam::model()->findAll('userid=:userid', array(':userid'=> $user->userid));
                         foreach ($exams as $exam){
                             $dis = $exam->discipline;
                             $mark = $exam->mark;
                             $nyear = (int)date('Y');
                             //$sql_exam = "declare sexeption_var string(4000); begin sexeption_var := :sexeption; a_p_abit_marks(:rn,:dis,:mark,:nyear,:nresult,sexeption_var); end;";
							 $sql_exam = "begin a_p_abit_marks(:rn,:dis,:mark,:nyear,:nresult,:sexeption); end;";

                             $s_exam = OCIParse($c, $sql_exam);
                             OCIBindByName($s_exam,":rn",$rn);
                             OCIBindByName($s_exam,":dis",$dis);
                             OCIBindByName($s_exam,":mark",$mark);
                             OCIBindByName($s_exam,":nyear",$nyear);
                             OCIBindByName($s_exam,":nresult",$nresult_exam, 1024);
                             OCIBindByName($s_exam,":sexeption",$sexeption_exam, 1024);
                             OCIExecute($s_exam, OCI_NO_AUTO_COMMIT);
                             $error_exam = $error_exam + $nresult_exam;
                             $sexam = '<br>'.$sexeption_exam;
                         }
                         if ($error_exam == 0 ) {
                             $text = $text.' Добавлены экзамены абитуриента<br>';

                             $u_plan = array();
                             $plans = UsersPlan::model()->findAll('userid=:userid', array(':userid'=>$user->userid));
                             foreach ($plans as $p){
                                 $cl_plan = Plan::model()->findByPK($p->planid);

                                 $plan = $cl_plan->plan_id;
                                 $planfin = $cl_plan->finsource;
                                 $priority = $p->priority;

                                 if (count($benefits) > 0 && $p->benefits){
                                     $lgot = 1;
                                 }
                                 else $lgot = 0;

                                 $sql_plan = "begin a_p_abit_plan2(:rn, :plan, :planfin, :priority, :lgot, :nresult,:sexception); end;";
                                 $s_plan = OCIParse($c, $sql_plan);
                                 OCIBindByName($s_plan,":rn",$rn);
                                 OCIBindByName($s_plan,":plan",$plan);
                                 OCIBindByName($s_plan,":planfin",$planfin);

                                 OCIBindByName($s_plan,":priority",$priority);
                                 OCIBindByName($s_plan,":lgot",$lgot);
                                 OCIBindByName($s_plan,":nresult",$nresult_plan, 1024);
                                 OCIBindByName($s_plan,":sexception",$sexeption_plan, 1024);
                                 OCIExecute($s_plan, OCI_NO_AUTO_COMMIT);
                             }

                             // добавляем планы
                             /*$sql_plan = "begin a_p_abit_plan(:rn, '80010000000000A2', '800100000000010C', '2', :nresult,:sexception); end;";
                             $s_plan = OCIParse($c, $sql_plan);
                             OCIBindByName($s_plan,":rn",$rn);
                             OCIBindByName($s_plan,":nresult",$nresult_plan);
                             OCIBindByName($s_plan,":sexception",$sexeption_plan);
                             OCIExecute($s_plan, OCI_NO_AUTO_COMMIT);*/
                             if ($nresult_plan == 0) {
                                 $text = $text.' Добавлен план абитуриента<br>';
                                 // добавляем фото
                                 $old_filename = 'photouser/'.$user->userid.'/'.$user->userid.'.jpeg';
                                 $new_filename = 'nfs/photo/'.$user->userid.'.jpeg';
                                 copy($old_filename, $new_filename);

                                 $spath = 'ABIT_PHOTO';
                                 $sfile = $user->userid.'.jpeg';
                                     $sql_foto = "begin a_p_abit_foto2(:rn, :spath,:sfile,:nresult,:sexeption); end;";
							     $s_foto = OCIParse($c, $sql_foto);
                                 OCIBindByName($s_foto,":rn",$rn);
                                 OCIBindByName($s_foto,":spath",$spath);
                                 OCIBindByName($s_foto,":sfile",$sfile);
                                 OCIBindByName($s_foto,":nresult",$nresult_foto, 1024);
                                 OCIBindByName($s_foto,":sexeption",$sexeption_foto, 1024);
                                 OCIExecute($s_foto, OCI_NO_AUTO_COMMIT);
                                 if ($nresult_foto == 0 ) {
                                     //Здесь должен быть commit
                                     OCICommit($c);
                                     $user->activated = 11;
                                     $user->save(false);
                                     $text = $text." Добавлено фото абитуриента<br>";
                                 } else{
                                    $error = $error.' Ошибка добавления фото абитуриента: '.iconv('Windows-1251','utf8',$sexeption_foto).'<br>';
                                    OCIRollback($c);
                                 }


                             } else {
                                 $error = $error.'Ошибка добавления плана абитуриента: '.iconv('Windows-1251','utf8',$sexeption_plan).'<br>';
                                 OCIRollback($c);
                             }

                         }  else {
                            $error = $error.'Ошибка добавления экзаменов абитуриента: '.iconv('Windows-1251','utf8',$sexam).'<br>';
							$error .= ' '.$dis;
                             $error .= ' '.$mark;
                             $error .= ' '.(int)date('Y');
							
                            OCIRollback($c);
                         }

                     } else {
                        $error = $error.'Ошибка добавления олимпиад абитуриента: '.iconv('Windows-1251','utf8',$solympic).'<br>';
                        OCIRollback($c);
                     }


                } else {
                    $error = $error.'Ошибка добавления льгот абитуриента: '.iconv('Windows-1251','utf8',$slgot).'<br>';
                    OCIRollback($c);
                }


        } else {
            $error= $error.'Ошибка добавления абитуриента: '.iconv('Windows-1251','utf8',$sexeption).'<br>';
			#$error = $error.' Ошибка добавления абитуриента '.$sexeption.'<br>';
            OCIRollback($c);
        }

        OCIRollback($c);

        // Отключаемся от базы данных
        OCILogoff($c);

        $this->render('inGal',array('text'=>$text,'error'=>$error));

    }

}
