<?php echo $this->Session->flash(); ?>

<script type="text/javascript">
    function switchTab(cls) {
        $('.tabs-content').hide();
        $('.tab-' + cls).show();
        $('.tab-li').removeClass('active');
        $('.tab-li-' + cls).addClass('active');
    }
</script>

<?php if (($this->Session->read('Auth.User')) && (($this->Session->read('Auth.User.id')) != $user['User']['id'])) : ?>
<div id="dialog" title="Message" style="display:none;">
    <div style="text-align:center;">
        <img src="/img/users/<?php echo $this->Session->read('Auth.User.id'); ?>/<?php echo $this->Session->read('Auth.User.id'); ?>_p.png">
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <img src="/img/message_forward.png">
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <img src="/img/users/<?php echo $user['User']['id']; ?>/<?php echo $user['User']['id']; ?>_p.png">
    </div>

    <?php echo $this->Form->create('Message', array('action' => 'send')); ?>

    <?php echo $this->Form->input('Message.user_id', array('label'=>false, 'type'=>'hidden', 'value'=>$this->Session->read('Auth.User.id'))); ?>
    <?php echo $this->Form->input('Message.object_id', array('label'=>false, 'type'=>'hidden', 'value'=>$user['User']['id'])); ?>

    <?php echo $this->Form->input('Message.text', array('label'=>false, 'type'=>'textarea')); ?>

    <?php echo $this->Form->submit('Send', array('class'=>'ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only')); ?>

    <?php echo $this->Form->end(); ?>
</div>
<?php endif; ?>

<div class="user profile">

    <div class="layout-left">
        <div id="profile-pic">
            <a href="/users/profile/<?php echo $user['User']['username']; ?>">
                <img src="<?php echo $user['User']['profile_pic']; ?>" alt="">
            </a>
        </div>

        <div id="profile-opt">
            <ul>
                <?php if (($this->Session->read('Auth.User')) && (($this->Session->read('Auth.User.id')) != $user['User']['id'])) : ?>
                <li>
                    <a target="" style="background-image: url('/img/friends_add.png');" class="profile-opt-links" href="/messages/addfriend/<?php echo $user['User']['id']; ?>" onclick="return confirm('Are you sure?')">Add as friend</a>
                </li>
                <li>
                    <a target="" style="background-image: url('/img/message.png');" class="profile-opt-links" href="javascript:void(0);" onclick="$('#dialog').dialog();">Send message</a>
                </li>
                <?php endif; ?>
                <li>
                    <a target="" style="background-image: url('/img/friends.png');" class="profile-opt-links" href="/users/friends/<?php echo $user['User']['id']; ?>">Friends</a>
                </li>
                <?php if (($this->Session->read('Auth.User')) && (($this->Session->read('Auth.User.id')) == $user['User']['id'])) : ?>
                <li>
                    <a target="" style="background-image: url('/img/album.png');" class="profile-opt-links" href="/images/upload_profile">Change profile picture</a>
                </li>
                <li>
                    <a target="" style="background-image: url('/img/profile.png');" class="profile-opt-links" href="/users/edit/<?php echo $user['User']['id']; ?>">Edit My Profile</a>
                </li>
                <?php if ($this->Session->read('Auth.User.group_id') == ROLE_ADMIN) : ?>
                <li style="border-top:1px solid #ccc;">
                    <a target="" style="background-image: url('/img/friends.png');" class="profile-opt-links" href="/users/index">User List</a>
                </li>
                <?php endif; ?>
                <?php endif; ?>
            </ul>
        </div>

        <div id="profile-info">
            <ul>
                <li>Profile Views: <?php echo $user['User']['hits']; ?> views</li>
                <li>Friends: <?php echo count($friends); ?> friends</li>
                <li>Last Login: <?php echo date("F j, Y", strtotime($user['User']['last_login_time'])); ?></li>
                <li>Joined: <?php echo date("F j, Y", strtotime($user['User']['created'])); ?></li>
            </ul>
        </div>
    </div>

    <div class="layout-mid">
        <div class="profile-status">
            <h2><?php echo $user['User']['name']; ?> </h2>
        </div>

        <div class="layout-container">

            <div class="tabs-content tab-wall">
                <div class="post-box">
                    <?php echo $this->Form->create('Post', array('action' => 'add')); ?>
                    <?php echo $this->Form->input('Post.text', array('label'=>false)); ?>
                    <?php echo $this->Form->submit('Share'); ?>
                    <?php echo $this->Form->end(); ?>
                </div>

                <?php if (count($posts) > 0) : ?>
                <ul class="feeds">
                <?php foreach ($posts as $post) : ?>
                    <li class="feed-item">
                        <div class="feed">
                            <div class="feed-item-photo">
                                <a href="/users/profile/<?php echo $post['User']['username']; ?>"><img src="/img/users/<?php echo $post['Post']['user_id']; ?>/<?php echo $post['Post']['user_id']; ?>_p.png" alt="" /></a>
                            </div>

                            <div class="feed_item_body">
                                <span class="feed-item-post">
                                    <a class="feed-item-username" href="/users/profile/<?php echo $post["User"]["username"]; ?>"><?php echo $post["User"]["name"]; ?></a>
                                    <span class="feed-item-bodytext">
                                        <?php echo $post["Post"]["text"]; ?>
                                    </span>
                                </span>

                                <div class="feed-items" style="background-image:url('/img/date.png');">
                                    <ul>
                                        <li><?php echo date("M j, H:i", strtotime($post["Post"]["created"])); ?></li>
                                        <li>
                                            <span> - </span>
                                            <a href="javascript:void(0);" onclick="toggleCommentBox(<?php echo $post['Post']['id']; ?>);">Comment</a>
                                        </li>
                                        <?php if ($loggedInUserId == $user['User']['id']) : ?>
                                        <li>
                                            <span> - </span>
                                            <a href="/posts/delete/<?php echo $post['Post']['id']; ?>" onclick="return confirm('Are you sure?')">Delete</a>
                                        </li>
                                        <?php endif; ?>
                                    </ul>
                                </div>

                                <div class="comments">
                                    <?php if (count($comments[$post['Post']['id']]) > 0) : ?>
                                    <ul>
                                    <?php for($i=0; $i<count($comments[$post['Post']['id']]); $i++) : ?>
                                        <li>
                                            <div class="commenter-photo">
                                                <a href="/users/profile/<?php echo $comments[$post['Post']['id']][$i]["User"]["username"]; ?>">
                                                    <img src="/img/users/<?php echo $comments[$post['Post']['id']][$i]["User"]["id"]; ?>/<?php echo $comments[$post['Post']['id']][$i]["User"]["id"]; ?>_p.png" alt="">
                                                </a>
                                            </div>

                                            <div class="comment-info">
                                                <span class="commenter">
                                                    <a href="/users/profile/<?php echo $comments[$post['Post']['id']][$i]["User"]["username"]; ?>"><?php echo $comments[$post['Post']['id']][$i]["User"]["name"]; ?></a>
                                                </span>

                                                <?php echo $comments[$post['Post']['id']][$i]["Comment"]["text"]; ?>

                                                <div class="comment-tools">
                                                    <ul>
                                                        <li><?php echo date("M j, H:i", strtotime($comments[$post['Post']['id']][$i]["Comment"]["created"])); ?></li>
                                                        <?php if ($loggedInUserId == $comments[$post['Post']['id']][$i]["User"]["id"]) : ?>
                                                        <li>
                                                            <span> - </span>
                                                            <a href="/comments/delete/<?php echo $comments[$post['Post']['id']][$i]["Comment"]["id"]; ?>" onclick="return confirm('Are you sure?')">Delete</a>
                                                        </li>
                                                        <?php endif; ?>
                                                    </ul>
                                                </div>
                                            </div>
                                        </li>
                                    <?php endfor ; ?>
                                    </ul>
                                    <?php endif; ?>

                                    <div class="wrap-comment-<?php echo $post['Post']['id']; ?> wrap-comments">
                                    <?php echo $this->Form->create('Comment', array('action' => 'add')); ?>
                                    <?php echo $this->Form->input('Comment.text', array('label'=>false, 'rows'=>2)); ?>
                                    <?php echo $this->Form->input('Comment.type', array('label'=>false, 'type'=>'hidden', 'value'=>POST)); ?>
                                    <?php echo $this->Form->input('Comment.parentId', array('label'=>false, 'type'=>'hidden', 'value'=>$post['Post']['id'])); ?>
                                    <?php echo $this->Form->submit('Post'); ?>
                                    <?php echo $this->Form->end(); ?>
                                    </div>
                                </div>
                            </div>


                            <div style="clear:both;"></div>
                        </div>
                    </li>
                <?php endforeach; ?>
                </ul>
                <?php endif; ?>
            </div>

        </div>
    </div>

    <div style="clear:both;"></div>
</div>