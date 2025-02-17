<?php
        include $_SERVER['DOCUMENT_ROOT'].'/assets/preferences.php';
        include $_SERVER['DOCUMENT_ROOT'].'/vendor/lang.php';
        include $_SERVER['DOCUMENT_ROOT'].'/vendor/default.php';
        include $_SERVER['DOCUMENT_ROOT'].'/vendor/connect.php';
        include $_SERVER['DOCUMENT_ROOT'].'/vendor/only-load.php';
?>
<?php
    $type_n = $_GET['type'];
    $limit_n = intval($_GET['limit']);
?>
<?php
        function convertTimePopupAlert($originalDate) {
                $day = date("d", strtotime($originalDate));
                $mounth = date("m", strtotime($originalDate));
                $year = date("Y", strtotime($originalDate));
                $hour = date("H", strtotime($originalDate));
                $minute = date("i", strtotime($originalDate));
                $second = date("s", strtotime($originalDate));

                return $day . ' ' . $mounth . ' ' . $year . ' ' . $hour . ':' . $minute;
        }
        function convertTimePopupAlert2($originalDate) {
                $day = date("d", strtotime($originalDate));
                $mounth = date("m", strtotime($originalDate));
                $year = date("Y", strtotime($originalDate));
                $hour = date("H", strtotime($originalDate));
                $minute = date("i", strtotime($originalDate));
                $second = date("s", strtotime($originalDate));

                return $day . '.' . $mounth . '.' . $year . ' ' . $hour . ':' . $minute . ':' . $second;
        }
        function get_browser_name($user_agent) {
            include $_SERVER['DOCUMENT_ROOT'].'/vendor/lang.php';

            $t = strtolower($user_agent);

            $t = " " . $t;

            if (strpos($t, 'dalvik') and strpos($t, 'build') and strpos($t, 'android')) {
                return 'Android App';
            } if (strpos($t, 'opera') or strpos($t, 'opr/')) {
                return 'Opera';
            } if (strpos($t, 'edge')) {
                return 'Edge';
            } if (strpos($t, 'chrome')) {
                return 'Chrome';
            } if (strpos($t, 'safari')) {
                return 'Safari';
            } if (strpos($t, 'firefox')) {
                return 'Firefox';
            }
            
           
            return $string_message_notification_device_unknown;
        }

        function getSocialNetwork($value='') {
                switch ($value) {
                        case 'vk':
                                return 'ВКонтакте';
                        
                        default:
                                return $value;
                }
        }

        function getEmotionName($value='') {
                return '<img src="/assets/icons/emotions/'.$value.'.png" class="notification-img-emotion">';
        }
?>
<?php if ($_COOKIE['USID'] == '') { ?>
        <h2 class="message"><?php echo $string['message_no_active_account']; ?></h2>
    <?php } else { ?>
        <?php
            $usid = $_COOKIE['USID'];

            // $url_user_alert = $default_api.'/user/data.php?id='.$usid.'&token='.$usid;
            // $result_user_alert = json_decode(file_get_contents($url_user_alert, false), true);

            $url_notifications = $default_api.'/user/notification/list.php?token='.$usid.'&type='.$type_n.'&limit='.$limit_n;
            $result_notifications = json_decode(file_get_contents($url_notifications, false), true);
        ?>
    <?php } ?>
    <?php if (sizeof($result_notifications) > 0) { ?>
        
        <?php $num_n = intval(sizeof($result_notifications)); ?>
        <div class="qak-popup-alert-container scroll-new" style="<?php if(isMobile()){echo'max-height:fit-content;';} ?>">
            <?php if (is_array($result_notifications)) { ?>
                <?php foreach($result_notifications as $key => $value) { ?>
                    <?php $num_n = $num_n - 1; ?>
                    <?php
                        $notify_readed = intval($value['notify_readed']);
                        $notify_id = intval($value['id']);
                        $notify_type = strval($value['notify_type']);
                        $notify_category = strval($value['notify_category']);

                        $result_title = strval($value['user_login']);
                        $result_message = strval($value['notify_message']);
                        $result_avatar = strval($value['user_avatar']);
                        $result_date = strval($value['notify_date']);
                    ?>
                    <?php
                        if ($notify_type == 'system') {
                            $result_title = $string['notification_name_system'];
                            $result_avatar = '/assets/images/qak-avatar-system.png';
                            
                            if ($notify_category == 'sign-in') {
                                $result_message = str_replace('%1s', convertTimePopupAlert2($value['notify_date']), $string['notification_message_system_sign_in']);
                                $result_message = str_replace('%2s', $value['notify_message'], $result_message);
                                $result_message = str_replace('%3s', get_browser_name($value['notify_message2']), $result_message);
                                $result_message = str_replace('%4s', '', $result_message);
                            }

                            if ($notify_category == 'sign-in-other') {
                                $result_message = str_replace('%1s', convertTimePopupAlert2($value['notify_date']), $string['notification_message_system_sign_in_other']);
                                $result_message = str_replace('%2s', $value['notify_message'], $result_message);
                                $result_message = str_replace('%3s', get_browser_name($value['notify_message2']), $result_message);
                                $result_message = str_replace('%4s', getSocialNetwork($value['notify_message3']), $result_message);
                            }

                            if ($notify_category == 'admin') {
                                $result_title = $string['notification_name_system_admin'];
                            }
                        }

                        if ($notify_type == 'secure') {
                            $result_title = $string['notification_name_secure'];
                            $result_avatar = '/assets/images/qak-avatar-secure.png';
                            
                            if ($notify_category == 'sign-in') {
                                $result_message = str_replace('%1s', convertTimePopupAlert2($value['notify_date']), $string['notification_message_secure_sign_in']);
                                $result_message = str_replace('%2s', $value['notify_message'], $result_message);
                                $result_message = str_replace('%3s', get_browser_name($value['notify_message2']), $result_message);
                                $result_message = str_replace('%4s', '', $result_message);
                            }
                        }

                        if ($notify_type == 'post') {
                            $result_title = $string['notification_name_post_emotion'];
                            $result_avatar = $result_avatar;

                            if ($notify_category == 'emotion') {
                                $result_avatar = '/assets/images/qak-avatar-post-emotion.png';
                                $result_message = str_replace('%1s', '@'.strval($value['user_login']), $string['notification_message_post_emotion']);
                                $result_message = str_replace('%2s', '<post-l onclick="showAlertPost('.$value['notify_message'].')">'.$value['notify_message2'].'</post-l>', $result_message);
                                $result_message = str_replace('%3s', getEmotionName($value['notify_message3']), $result_message);
                            }
                            // if ($notify_category == 'rate') {
                            //  $result_avatar = '/assets/images/qak-avatar-post-like.png';
                            //  $result_message = str_replace('%1s', '@'.strval($value['user_login']), $string_message_post_rate);
                            //  $result_message = str_replace('%2s', '<post-l onclick="showAlertPost('.$value['notify_message'].')">'.$value['notify_message2'].'</post-l>', $result_message);
                            // }
                            // if ($notify_category == 'like') {
                            //  $result_avatar = '/assets/images/qak-avatar-post-like.png';
                            //  $result_message = str_replace('%1s', '@'.strval($value['user_login']), $string_message_post_like);
                            //  $result_message = str_replace('%2s', '<post-l onclick="showAlertPost('.$value['notify_message'].')">'.$value['notify_message2'].'</post-l>', $result_message);
                            // }
                            // if ($notify_category == 'dislike') {
                            //  $result_avatar = '/assets/images/qak-avatar-post-dislike.png';
                            //  $result_message = str_replace('%1s', '@'.strval($value['user_login']), $string_message_post_dislike);
                            //  $result_message = str_replace('%2s', '<post-l onclick="showAlertPost('.$value['notify_message'].')">'.$value['notify_message2'].'</post-l>', $result_message);
                            // }
                            if ($notify_category == 'comment') {
                                $result_avatar = '/assets/images/qak-avatar-post-comment.png';
                                $result_title = $string['notification_name_post_comment'];
                                $result_message = str_replace('%1s', '@'.strval($value['user_login']), $string['notification_message_post_comment']);
                                $result_message = str_replace('%2s', '<post-l onclick="showAlertPost('.$value['notify_message'].')">'.$value['notify_message2'].'</post-l>', $result_message);
                                $result_message = str_replace('%3s', strval($value['notify_message3']), $result_message);
                            }
                            if ($notify_category == 'comment-reply') {
                                $result_avatar = '/assets/images/qak-avatar-post-comment-reply.png';
                                $result_title = $string['notification_name_post_comment_reply'];
                                $result_message = str_replace('%1s', '@'.strval($value['user_login']), $string['notification_message_post_comment_reply']);
                                $result_message = str_replace('%3s', '<post-l onclick="showAlertPost('.$value['notify_message'].')">'.$value['notify_message2'].'</post-l>', $result_message);
                                $result_message = str_replace('%2s', strval($value['notify_message3']), $result_message);
                            }
                            if ($notify_category == 'user-marker') {
                                $result_avatar = '/assets/images/qak-avatar-user-marked.png';
                                $result_title = $string['notification_name_post_user_marker'];
                                $result_message = str_replace('%1s', '@'.strval($value['user_login']), $string['notification_message_post_user_marker']);
                                $result_message = str_replace('%2s', '<post-l onclick="showAlertPost(\''.$value['notify_message'].'\')">'.$value['notify_message2'].'</post-l>', $result_message);
                            }
                        }

                        if ($notify_type == 'comment') {
                            $result_title = $string['notification_name_comment'];
                            $result_avatar = $result_avatar;

                            if ($notify_category == 'like') {
                                $result_title = $string['notification_name_comment_like'];
                                $result_message = str_replace('%1s', '@'.strval($value['user_login']), $string['notification_message_comment_like']);
                                $result_message = str_replace('%2s', '<post-l onclick="goAlertPost(\''.$value['notify_message'].'\', \''.$value['notify_message2'].'\')">'.$value['notify_message3'].'</post-l>', $result_message);
                            }
                        }

                        if ($notify_type == 'user') {
                            $result_title = '@'.strval($value['user_login']);
                            $result_avatar = $result_avatar;
                            
                            if ($notify_category == 'followed') {
                                $result_message = str_replace('%1s', '@'.strval($value['user_login']), $string['notification_message_user_followed']);
                            }
                            if ($notify_category == 'unfollowed') {
                                $result_message = str_replace('%1s', '@'.strval($value['user_login']), $string['notification_message_user_unfollowed']);
                            }
                        }

                        if ($notify_type == 'follow') {
                            $result_title = '@'.strval($value['user_login']);
                            $result_avatar = $result_avatar;
                            
                            if ($notify_category == 'confirm') {
                                $result_message = str_replace('%1s', '@'.strval($value['user_login']), $string['notification_message_follow_confirm']);
                            }
                            if ($notify_category == 'reject') {
                                $result_message = str_replace('%1s', '@'.strval($value['user_login']), $string['notification_message_follow_reject']);
                            }
                        }
                    ?>


                    <div class="qak-popup-alert-item" id="qak-popup-alert-item-<?php echo $notify_id; ?>">
                        <img src="<?php echo $result_avatar; ?>" class="qak-popup-alert-item-avatar" onerror="this.src = '/assets/images/qak-avatar-v3.png'">
                        <div class="qak-popup-alert-item-content">
                            <h2 class="qak-popup-alert-item-title">
                                <?php echo $result_title; ?>
                                <?php if (intval($value['user_verification']) == 1 and $notify_type == 'user' or $notify_type == 'follow') { ?>
                                    <verification-user></verification-user>
                                <?php } ?>
                                <!-- <divider-title-p-a></divider-title-p-a> -->
                                <div class="cont-dat">
                                    <font><?php echo convertTimeRus($result_date); ?></font>
                                    <?php if ($notify_readed == 1) { ?>
                                        <font><?php echo $string['text_notification_readed']; ?></font>
                                    <?php } ?>
                                </div>
                            </h2>
                            <h2 class="qak-popup-alert-item-message"><?php echo $result_message; ?></h2>
                        </div>
                    </div>

                    <?php if ($num_n != 0) { ?>
                        <hr>
                    <?php } ?>


                <?php } ?>
                <h5 class="qak-notify-limit"><?php echo str_replace('%1s', intval(sizeof($result_notifications)), $string['message_show_last_notifications']); ?></h5>
            <?php } ?>
        </div>
        <script type="text/javascript">
            readNotofy(0); // <------------ Читаем все уведомления
        </script>


    <?php } else { ?>
        <h2 class="message_popup"><?php echo $string['message_no_notifications']; ?></h2>
    <?php } ?>