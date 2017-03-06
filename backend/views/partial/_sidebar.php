<?php
/**
 * Created by dinhty.luu@gmail.com
 * Date: 03/03/2017
 * Time: 21:09
 */
use common\modules\adminUser\business\BusinessAdminUser;
use yii\helpers\Url;

$listProvidedActions = BusinessAdminUser::getProvidedActions(adminuser()->role_id);
$listSideNav = [
    'dashboard' => [
        'href' => Url::home(true),
        'icon' => 'fa fa-tasks',
        'permission' => true,
        'title' => Yii::t('app', 'Dashboard')
    ],
    'ticket' => [
        'icon' => 'fa fa-ticket',
        'permission' => true,
        'title' => Yii::t('app', 'Ticket'),
        'dropdownMenu' => [
            [
                'href' => 'ticket/dashboard',
                'title' => Yii::t('app', 'Dashboard'),
                'permission' => isset($listProvidedActions['ticket']['Dashboard'])
            ],
            [
                'href' => 'customer/index',
                'title' => Yii::t('app', 'List Ticket'),
                'permission' => isset($listProvidedActions['ticket']['Index'])
            ],
            [
                'href' => 'customer/config',
                'title' => Yii::t('app', 'Config'),
                'permission' => isset($listProvidedActions['ticket']['Config'])
            ]
        ]
    ],
    'customer' => [
        'icon' => 'fa fa-users',
        'permission' => true,
        'title' => Yii::t('app', 'Customer'),
        'dropdownMenu' => [
            [
                'href' => 'customer/dashboard',
                'title' => Yii::t('app', 'Dashboard'),
                'permission' => isset($listProvidedActions['customer']['Dashboard'])
            ],
            [
                'href' => 'customer/index',
                'title' => Yii::t('app', 'List Customer'),
                'permission' => isset($listProvidedActions['customer']['Index'])
            ],
            [
                'href' => 'customer/create',
                'title' => Yii::t('app', 'Create Customer'),
                'permission' => isset($listProvidedActions['customer']['Create'])
            ],
            [
                'href' => 'customer/config',
                'title' => Yii::t('app', 'Customer Config'),
                'permission' => isset($listProvidedActions['customer']['Config'])
            ]
        ]
    ],
    'systemSetting' => [
        'icon' => 'fa fa-cogs',
        'permission' => true,
        'title' => Yii::t('app', 'System Setting'),
        'dropdownMenu' => [
            [
                'href' => 'systemSetting/index',
                'title' => Yii::t('app', 'Manage'),
                'permission' => isset($listProvidedActions['systemSetting']['Index'])
            ],
            [
                'href' => 'systemSetting/create',
                'title' => Yii::t('app', 'Create'),
                'permission' => isset($listProvidedActions['systemSetting']['Create'])
            ],
        ]
    ],
];

?>
<aside class="app-sidebar" id="sidebar">
    <div class="sidebar-header">
        <a class="sidebar-brand" href="<?= Url::home(true); ?>"><span class="highlight">Lotto</span>
            CMS</a>
        <button type="button" class="sidebar-toggle">
            <i class="fa fa-times"></i>
        </button>
    </div>
    <div class="sidebar-menu">
        <ul class="sidebar-nav">
            <?php foreach ($listSideNav as $key => $sideNav) : ?>
                <li class="<?= isset($sideNav['dropdownMenu']) ? "dropdown" : null; ?>">
                    <a href="<?= isset($sideNav['href']) ? $sideNav['href'] : '#'; ?>"
                       class="<?= isset($sideNav['dropdownMenu']) ? 'dropdown-toggle' : null; ?>"
                       data-toggle="<?= isset($sideNav['dropdownMenu']) ? 'dropdown-toggle' : null; ?>">
                        <div class="icon">
                            <i class="<?= $sideNav['icon']; ?>" aria-hidden="true"></i>
                        </div>
                        <div class="title"><?= $sideNav['title']; ?></div>
                    </a>
                    <?php if (isset($sideNav['dropdownMenu'])) : ?>
                        <div class="dropdown-menu">
                            <ul>
                                <li class="section"><i class="fa fa-file-o"
                                                       aria-hidden="true"></i><?= $sideNav['title']; ?></li>
                                <?php foreach ($sideNav['dropdownMenu'] as $k => $value) : ?>
                                    <li><a href="<?= $value['href']; ?>"><?= $value['title']; ?></a></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif;; ?>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</aside>


<!--            <li>-->
<!--                <a href="../messaging.html">-->
<!--                    <div class="icon">-->
<!--                        <i class="fa fa-comments" aria-hidden="true"></i>-->
<!--                    </div>-->
<!--                    <div class="title">Messaging</div>-->
<!--                </a>-->
<!--            </li>-->
<!--            <li class="dropdown active">-->
<!--                <a href="#" class="dropdown-toggle" data-toggle="dropdown">-->
<!--                    <div class="icon">-->
<!--                        <i class="fa fa-cube" aria-hidden="true"></i>-->
<!--                    </div>-->
<!--                    <div class="title">UI Kits</div>-->
<!--                </a>-->
<!--                <div class="dropdown-menu">-->
<!--                    <ul>-->
<!--                        <li class="section"><i class="fa fa-file-o" aria-hidden="true"></i> UI Kits</li>-->
<!--                        <li><a href="../uikits/customize.html">Customize</a></li>-->
<!--                        <li><a href="../uikits/components.html">Components</a></li>-->
<!--                        <li><a href="../uikits/card.html">Card</a></li>-->
<!--                        <li><a href="../uikits/form.html">Form</a></li>-->
<!--                        <li><a href="../uikits/table.html">Table</a></li>-->
<!--                        <li><a href="../uikits/icons.html">Icons</a></li>-->
<!--                        <li class="line"></li>-->
<!--                        <li class="section"><i class="fa fa-file-o" aria-hidden="true"></i> Advanced Components</li>-->
<!--                        <li><a href="../uikits/pricing-table.html">Pricing Table</a></li>-->
<!--                        <!-- <li><a href="../uikits/timeline.html">Timeline</a></li> -->-->
<!--                        <li><a href="../uikits/chart.html">Chart</a></li>-->
<!--                    </ul>-->
<!--                </div>-->
<!--            </li>-->
<!--            <li class="dropdown">-->
<!--                <a href="#" class="dropdown-toggle" data-toggle="dropdown">-->
<!--                    <div class="icon">-->
<!--                        <i class="fa fa-file-o" aria-hidden="true"></i>-->
<!--                    </div>-->
<!--                    <div class="title">Pages</div>-->
<!--                </a>-->
<!--                <div class="dropdown-menu">-->
<!--                    <ul>-->
<!--                        <li class="section"><i class="fa fa-file-o" aria-hidden="true"></i> Admin</li>-->
<!--                        <li><a href="../pages/form.html">Form</a></li>-->
<!--                        <li><a href="../pages/profile.html">Profile</a></li>-->
<!--                        <li><a href="../pages/search.html">Search</a></li>-->
<!--                        <li class="line"></li>-->
<!--                        <li class="section"><i class="fa fa-file-o" aria-hidden="true"></i> Landing</li>-->
<!--                        <li><a href="../pages/login.html">Login</a></li>-->
<!--                        <li><a href="../pages/register.html">Register</a></li>-->
<!--                    </ul>-->
<!--                </div>-->
<!--            </li>-->
<!--        </ul>-->
<!--    </div>-->
<!--    <div class="sidebar-footer">-->
<!--        <ul class="menu">-->
<!--            <li>-->
<!--                <a href="/" class="dropdown-toggle" data-toggle="dropdown">-->
<!--                    <i class="fa fa-cogs" aria-hidden="true"></i>-->
<!--                </a>-->
<!--            </li>-->
<!--            <li><a href="#"><span class="flag-icon flag-icon-th flag-icon-squared"></span></a></li>-->
<!--        </ul>-->
<!--    </div>-->