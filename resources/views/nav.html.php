<?php print_r(json_encode($_POST)); echo '<br />';?>
<nav class="nav navbar navbar-expand-lg navbar-light bg-light sticky-top container-fullwidth d-flex" role="navigation">
    <div class="container"> 
        <a class="navbar-brand" target="_blank" rel="noopener noreferrer" href="https://activepublishing.fr/fr/accueil">
            <img src="/resources/images/logo.png" alt="Active Publishing" width="300px" height="auto" />
        </a>
        <button class="navbar-toggler border-0" type="button" data-toggle="collapse" data-target="#exCollapsingNavbar">
            &#9776;
        </button>
        <div class="collapse navbar-collapse" id="exCollapsingNavbar">
            <ul class="nav navbar-nav ml-auto">
                <?php
                foreach($pages as $page) {
                    if($page['visibility_level'] <= $userLevel) {
                        ?>
                            <li class="nav-item">
                                <input type="submit" 
                                   name="page" 
                                   class="btn btn-outline-secondary mx-1 my-1" 
                                   value="<?= (isset($page['label']) && is_string($page['label']) ? $page['label'] : 'not set');?>" 
                                   />
                            </li>
                        <?php
                    }
                }
                if($siteNeedsLogin) {
                    if($is_authUser) {
                        ?>
                        <button type="button" name="nav-action" class="btn btn-outline-secondary mx-1 my-1">
                            Logout
                        </button>
                        <?php
                    } else {
                    ?>
                        <li class="dropdown order-1">
                            <button type="button" id="dropdownMenu1" data-toggle="dropdown" class="btn btn-outline-secondary dropdown-toggle mx-1 my-1">
                                Login <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-right mt-2">
                               <li class="px-3 py-2">
                                   <form class="form" role="form" method="post">
                                        <div class="form-group">
                                            <input id="unameInput" placeholder="Username" class="form-control form-control-sm" type="text" required="">
                                        </div>
                                        <div class="form-group">
                                            <input id="passwordInput" placeholder="Password" class="form-control form-control-sm" type="text" required="">
                                        </div>
                                        <div class="form-group">
                                            <button type="submit" name="nav-action" class="btn btn-primary btn-block">Login</button>
                                        </div>
                                        <div class="form-group text-center">
                                            <small><a href="#" data-toggle="modal" data-target="#modalPassword">Forgot password?</a></small>
                                        </div>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    <?php
                    }
                }
                ?>
            </ul>
        </div>
    </div>
</nav>

<div id="modalPassword" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Forgot password</h3>
                <button type="button" class="close font-weight-light" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                <p>Reset your password..</p>
            </div>
            <div class="modal-footer">
                <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
                <button class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>
