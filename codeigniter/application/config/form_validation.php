<?php 
	$config = array(
	     'editShowcase' => array(
			array(
				'field' => 'title',
				'label' => 'Title',
				'rules' => 'trim|xss_clean|required'
			),
			array(
				'field' => 'date_started',
				'label' => 'Start date',
				'rules' => 'xss_clean|required|date'
			),
			array(
				'field' => 'date_completed',
				'label' => 'Completion date',
				'rules' => 'xss_clean|required|date'
			),
			array(
				'field' => 'description',
				'label' => 'Description',
				'rules' => 'xss_clean|required'
			),
			array(
				'field' => 'deliverable',
				'label' => 'Deliverable',
				'rules' => 'xss_clean|required'
			),
			array(
				'field' => 'contractor',
				'label' => 'Contractor',
				'rules' => 'xss_clean|required'
			),
			array(
				'field' => 'owner',
				'label' => 'Owner',
				'rules' => 'xss_clean|required'
			)
		),
		'addShowcaseLogo' => array(
			array(
				'field' => 'logo_alt',
				'label' => 'Image title',
				'rules' => 'trim|xss_clean|required'
			)
		),
		'addShowcaseScreenshot' => array(
			array(
				'field' => 'screenshot_alt',
				'label' => 'Image title',
				'rules' => 'trim|xss_clean|required'
			)
		),
		'addShowcaseLink' => array(
			array(
				'field' => 'related_link',
				'label' => 'Link',
				'rules' => 'trim|xss_clean|_validUrl|required'
			),
			array(
				'field' => 'related_link_title',
				'label' => 'Link title',
				'rules' => 'trim|xss_clean|required'
			)
		),
		'addShowcase' => array(
			array(
				'field' => 'addTitle',
				'label' => 'showcase',
				'rules' => 'trim|xss_clean|required|_checkExists[showcase]'
			)
		),
		'addSoftware' => array(
			array(
				'field' => 'addName',
				'label' => 'software',
				'rules' => 'trim|xss_clean|required|_checkExists[software]'
			)
		),
		'editSoftware' => array(
			array(
				'field' => 'editName',
				'label' => 'software',
				'rules' => 'trim|xss_clean|required|_checkExists[software]'
			)
		),
		'addLanguages' => array(
			array(
				'field' => 'addName',
				'label' => 'language',
				'rules' => 'trim|xss_clean|required|_checkExists[languages]'
			)
		),
		'editLanguages' => array(
			array(
				'field' => 'editName',
				'label' => 'languages',
				'rules' => 'trim|xss_clean|required|_checkExists[languages]'
			)
		),
		'addFrameworks' => array(
			array(
				'field' => 'addName',
				'label' => 'framework',
				'rules' => 'trim|xss_clean|required|_checkExists[frameworks]'
			)
		),
		'editFrameworks' => array(
			array(
				'field' => 'editName',
				'label' => 'framework',
				'rules' => 'trim|xss_clean|required|_checkExists[frameworks]'
			)
		),
		'addSkill' => array(
			array(
				'field' => 'addName',
				'label' => 'skill',
				'rules' => 'trim|xss_clean|required|_checkExists[skills]'
			)
		),
		'editSkill' => array(
			array(
				'field' => 'editName',
				'label' => 'skill',
				'rules' => 'trim|xss_clean|required|_checkExists[skills]'
			)
		),
		'addExpertise' => array(
			array(
				'field' => 'addName',
				'label' => 'expertise',
				'rules' => 'trim|xss_clean|required|_checkExists[expertise]'
			)
		),
		'editExpertise' => array(
			array(
				'field' => 'editExpertise',
				'label' => 'expertise',
				'rules' => 'trim|xss_clean|required|_checkExists[expertise]'
			)
		),
		'addExperience' => array(
			array(
				'field' => 'addName',
				'label' => 'experience',
				'rules' => 'trim|xss_clean|required|_checkExists[experience]'
			)
		),
		'editExperience' => array(
			array(
				'field' => 'editName',
				'label' => 'experience',
				'rules' => 'trim|xss_clean|required|_checkExists[experience]'
			)
		),
		'editProfile' => array(
			array(
				'field' => 'years_experience',
				'label' => 'years experience',
				'rules' => 'trim|xss_clean|required|numeric'
			)
		),
		'addProfileImage' => array(
			array(
				'field' => 'profile',
				'label' => 'photo',
				'rules' => 'trim|xss_clean|required|prep_for_form'
			),
		),
		'addProfileBackImage' => array(
			array(
				'field' => 'profile_back',
				'label' => 'photo (back)',
				'rules' => 'trim|xss_clean|required|prep_for_form'
			)
		), 
		'editProfileSkills' => array(
			array(
				'field' => 'editSkills',
				'label' => 'skill',
				'rules' => 'trim|xss_clean|_reqDelimiter[:]|required'
			)
		),
		'editSkillsOrder' => array(
			array(
				'field' => 'editSkillsOrder',
				'label' => 'skill order',
				'rules' => 'trim|xss_clean|required'
			)
		),
		'addProfileSkills' => array(
			array(
				'field' => 'skills',
				'label' => 'skill',
				'rules' => 'trim|xss_clean|required|_reqDelimiter[:]|prep_for_form'
			),
			array(
				'field' => 'skillsOrder',
				'label' => 'order',
				'rules' => 'trim|xss_clean|numeric'
			)
		),
		'editProfileAchievements' => array(
			array(
				'field' => 'editAchievements',
				'label' => 'achievement',
				'rules' => 'trim|xss_clean|required'
			)
		),
		'editAchievementsOrder' => array(
			array(
				'field' => 'editAchievementsOrder',
				'label' => 'achievement order',
				'rules' => 'trim|xss_clean|required'
			)
		),
		'addProfileAchievements' => array(
			array(
				'field' => 'achievements',
				'label' => 'achievement',
				'rules' => 'trim|xss_clean|required|prep_for_form'
			),
			array(
				'field' => 'achievementsOrder',
				'label' => 'Order',
				'rules' => 'trim|xss_clean|required|numeric'
			)
		),
		'addBlog' => array(
			array(
				'field' => 'addName',
				'label' => 'blog',
				'rules' => 'trim|xss_clean|required|_checkExists[blog]'
			)
		),
		'editBlog' => array(
			array(
				'field' => 'title',
				'label' => 'Title',
				'rules' => 'trim|xss_clean|required'
			),
			array(
				'field' => 'date_completed',
				'label' => 'Completion date',
				'rules' => 'xss_clean|required|date'
			),
			array(
				'field' => 'url',
				'label' => 'URL',
				'rules' => 'xss_clean|required'
			),
			array(
				'field' => 'description',
				'label' => 'Description',
				'rules' => 'required'
			)
		),
		'addBlogLogo' => array(
			array(
				'field' => 'logo_alt',
				'label' => 'Image title',
				'rules' => 'trim|xss_clean|required'
			)
		),
		'addBlogScreenshot' => array(
			array(
				'field' => 'screenshot_alt',
				'label' => 'Image title',
				'rules' => 'trim|xss_clean|required'
			)
		),
		'addBlogLink' => array(
			array(
				'field' => 'related_link',
				'label' => 'Link',
				'rules' => 'trim|xss_clean|_validUrl|required|_checkExists[blog_relatedlinks]'
			),
			array(
				'field' => 'related_link_title',
				'label' => 'Link title',
				'rules' => 'trim|xss_clean|required'
			)
		),
		'register' => array(
				array(
						'field' => 'username',
						'label' => 'username',
						'rules' => 'required|callback_checkDefault|alpha_numeric'
				), 
				array (
						'field' => 'email',
						'label' => 'email',
						'rules' => 'required|valid_email|callback_checkDefault'
				),
				array (
						'field' => 'password',
						'label' => 'password',
						'rules' => 'required|callback_checkDefault|callback_checkPassword'
				), 
				array (
						'field' => 'csrf_secure',
						'label' => 'csrf_secure',
						'rules' => 'callback_checkCSRF'
				)
		),
		'login' => array(
				array (
						'field' => 'email',
						'label' => 'email',
						'rules' => 'required|valid_email|callback_checkDefault'
				),
				array (
						'field' => 'password',
						'label' => 'password',
						'rules' => 'required|callback_checkDefault'
				),
				array (
						'field' => 'csrf_secure',
						'label' => 'csrf_secure',
						'rules' => 'callback_checkCSRF'
				)
		),
		'photo' => array(
				array (
						'field' => 'data',
						'label' => 'data',
						'rules' => 'callback_dataValidate'
						//'rules' => 'valid_base64|callback_dataValidate'
				),
				array (
						'field' => 'name',
						'label' => 'name',
						'rules' => 'required|max_length[75]|callback_sanitiseFilename'
				),
				array (
						'field' => 'csrf_secure',
						'label' => 'csrf_secure',
						'rules' => 'required|callback_checkCSRF'
				)
		)
	);
?>
