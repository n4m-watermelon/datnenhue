<?php

return [
    [
        'name' => 'Lịch sử hoạt động',
        'flag' => 'admin::audit-log.index'
    ],
    [
        'name'        => 'Xóa',
        'flag'        => 'admin::audit-log.destroy',
        'parent_flag' => 'admin::audit-log.index',
    ],
    [
        'name'        => 'Đối tác',
        'flag'        => 'admin::partners.index',
    ],
    [
        'name'        => 'Thêm mới',
        'flag'        => 'admin::partners.create',
        'parent_flag' => 'admin::partners.index',
    ],
    [
        'name'        => 'Chỉnh sửa',
        'flag'        => 'admin::partners.edit',
        'parent_flag' => 'admin::partners.index',
    ],
    [
        'name'        => 'Xóa',
        'flag'        => 'admin::partners.destroy',
        'parent_flag' => 'admin::partners.index',
    ],

    [
        'name'        => 'Quản trị viên',
        'flag'        => 'admin::users.index',
    ],
    [
        'name'        => 'Thêm mới',
        'flag'        => 'admin::users.create',
        'parent_flag' => 'admin::users.index',
    ],
    [
        'name'        => 'Chỉnh sửa',
        'flag'        => 'admin::users.edit',
        'parent_flag' => 'admin::users.index',
    ],
    [
        'name'        => 'Xóa',
        'flag'        => 'admin::users.destroy',
        'parent_flag' => 'admin::users.index',
    ],

    [
        'name'        => 'Nhóm và phân quyền',
        'flag'        => 'admin::roles.index',
    ],
    [
        'name'        => 'Thêm mới',
        'flag'        => 'admin::roles.create',
        'parent_flag' => 'admin::roles.index',
    ],
    [
        'name'        => 'Chỉnh sửa',
        'flag'        => 'admin::roles.edit',
        'parent_flag' => 'admin::roles.index',
    ],
    [
        'name'        => 'Xóa',
        'flag'        => 'admin::roles.destroy',
        'parent_flag' => 'admin::roles.index',
    ],
    [
        'name'        => 'Quản lý Bài viết',
        'flag'        => 'admin::articles.index',
    ],
    [
        'name'        => 'Thêm mới',
        'flag'        => 'admin::articles.create',
        'parent_flag' => 'admin::articles.index',
    ],
    [
        'name'        => 'Chỉnh sửa',
        'flag'        => 'admin::articles.edit',
        'parent_flag' => 'admin::articles.index',
    ],
    [
        'name'        => 'Xóa',
        'flag'        => 'admin::articles.destroy',
        'parent_flag' => 'admin::articles.index',
    ],
    [
        'name'        => 'Categories',
        'flag'        => 'admin::categories.index'
    ],
    [
        'name'        => 'Component',
        'flag'        => 'admin::categories.indexSpec',
        'parent_flag' => 'admin::categories.index'
    ],
    [
        'name'        => 'Create Component',
        'flag'        => 'admin::categories.createSpec',
        'parent_flag' => 'admin::categories.index'
    ],
    [
        'name'        => 'Thêm mới',
        'flag'        => 'admin::categories.create',
        'parent_flag' => 'admin::categories.index'
    ],
    [
        'name'        => 'Chỉnh sửa',
        'flag'        => 'admin::categories.edit',
        'parent_flag' => 'admin::categories.index'
    ],
    [
        'name'        => 'Xóa',
        'flag'        => 'admin::categories.destroy',
        'parent_flag' => 'admin::categories.index'
    ],
    [
        'name'        => 'Quản lý Trang',
        'flag'        => 'admin::pages.index',
    ],
    [
        'name'        => 'Thêm mới',
        'flag'        => 'admin::pages.create',
        'parent_flag' => 'admin::pages.index',
    ],
    [
        'name'        => 'Chỉnh sửa',
        'flag'        => 'admin::pages.edit',
        'parent_flag' => 'admin::pages.index',
    ],
    [
        'name'        => 'Xóa',
        'flag'        => 'admin::pages.destroy',
        'parent_flag' => 'admin::pages.index',
    ],
    [
        'name' => 'Giao Diện',
        'flag' => 'core.appearance',
    ],
    [
        'name'        => 'Khối trang chủ',
        'flag'        => 'admin::blocks.index',
        'parent_flag' => 'core.appearance',
    ],
    [
        'name'        => 'Thêm mới',
        'flag'        => 'admin::blocks.create',
        'parent_flag' => 'admin::blocks.index',
    ],
    [
        'name'        => 'Chỉnh sửa',
        'flag'        => 'admin::blocks.edit',
        'parent_flag' => 'admin::blocks.index',
    ],
    [
        'name'        => 'Xóa',
        'flag'        => 'admin::blocks.destroy',
        'parent_flag' => 'admin::blocks.index',
    ],
    [
        'name'        => 'Nhóm Trình Đơn',
        'flag'        => 'admin::menu_groups.index',
        'parent_flag' => 'core.appearance',
    ],
    [
        'name'        => 'Thêm mới',
        'flag'        => 'admin::menu_groups.create',
        'parent_flag' => 'admin::menu_groups.index',
    ],
    [
        'name'        => 'Chỉnh sửa',
        'flag'        => 'admin::menu_groups.edit',
        'parent_flag' => 'admin::menu_groups.index',
    ],
    [
        'name'        => 'Xóa',
        'flag'        => 'admin::menu_groups.destroy',
        'parent_flag' => 'admin::menu_groups.index',
    ],
    [
        'name'        => 'Trình Đơn',
        'flag'        => 'admin::menu_items.index',
        'parent_flag' => 'core.appearance',
    ],
    [
        'name'        => 'Thêm mới',
        'flag'        => 'admin::menu_items.create',
        'parent_flag' => 'admin::menu_items.index',
    ],
    [
        'name'        => 'Chỉnh sửa',
        'flag'        => 'admin::menu_items.edit',
        'parent_flag' => 'admin::menu_items.index',
    ],
    [
        'name'        => 'Xóa',
        'flag'        => 'admin::menu_items.destroy',
        'parent_flag' => 'admin::menu_items.index',
    ],
    [
        'name'        => 'Nhóm Slider',
        'flag'        => 'admin::slider_groups.index',
        'parent_flag' => 'core.appearance',
    ],
    [
        'name'        => 'Thêm mới',
        'flag'        => 'admin::slider_groups.create',
        'parent_flag' => 'admin::slider_groups.index',
    ],
    [
        'name'        => 'Chỉnh sửa',
        'flag'        => 'admin::slider_groups.edit',
        'parent_flag' => 'admin::slider_groups.index',
    ],
    [
        'name'        => 'Xóa',
        'flag'        => 'admin::slider_groups.destroy',
        'parent_flag' => 'admin::slider_groups.index',
    ],
    [
        'name'        => 'Ảnh Slider',
        'flag'        => 'admin::sliders.index',
        'parent_flag' => 'core.appearance',
    ],
    [
        'name'        => 'Thêm mới',
        'flag'        => 'admin::sliders.create',
        'parent_flag' => 'admin::sliders.index',
    ],
    [
        'name'        => 'Chỉnh sửa',
        'flag'        => 'admin::sliders.edit',
        'parent_flag' => 'admin::sliders.index',
    ],
    [
        'name'        => 'Xóa',
        'flag'        => 'admin::sliders.destroy',
        'parent_flag' => 'admin::sliders.index',
    ],
    [
        'name' => 'Bất động sản',
        'flag' => 'core.estates',
    ],
    [
        'name'        => 'Sản phẩm',
        'flag'        => 'admin::estates.index',
        'parent_flag' => 'core.estates'
    ],
    [
        'name'        => 'Thêm mới',
        'flag'        => 'admin::estates.create',
        'parent_flag' => 'admin::estates.index'
    ],
    [
        'name'        => 'Chỉnh sửa',
        'flag'        => 'admin::estates.edit',
        'parent_flag' => 'admin::estates.index'
    ],
    [
        'name'        => 'Xóa',
        'flag'        => 'admin::estates.destroy',
        'parent_flag' => 'admin::estates.index'
    ],
    [
        'name'        => 'Duyệt sản phẩm',
        'flag'        => 'admin::approved.destroy',
        'parent_flag' => 'admin::estates.index'
    ],
    [
        'name'        => 'Import Sản phẩm',
        'flag'        => 'admin::estates.import',
        'parent_flag' => 'admin::estates.index'
    ],
    [
        'name'        => 'Đơn vị sản phẩm',
        'flag'        => 'admin::units.index',
        'parent_flag' => 'core.estates'
    ],
    [
        'name'        => 'Thêm mới',
        'flag'        => 'admin::units.create',
        'parent_flag' => 'admin::units.index'
    ],
    [
        'name'        => 'Chỉnh sửa',
        'flag'        => 'admin::units.edit',
        'parent_flag' => 'admin::units.index'
    ],
    [
        'name'        => 'Xóa',
        'flag'        => 'admin::units.destroy',
        'parent_flag' => 'admin::units.index'
    ],
    [
        'name'        => 'Khoảng giá',
        'flag'        => 'admin::range_prices.index',
        'parent_flag' => 'core.estates'
    ],
    [
        'name'        => 'Thêm mới',
        'flag'        => 'admin::range_prices.create',
        'parent_flag' => 'admin::range_prices.index'
    ],
    [
        'name'        => 'Chỉnh sửa',
        'flag'        => 'admin::range_prices.edit',
        'parent_flag' => 'admin::range_prices.index'
    ],
    [
        'name'        => 'Xóa',
        'flag'        => 'admin::range_prices.destroy',
        'parent_flag' => 'admin::range_prices.index'
    ],
    [
        'name'        => 'Khoảng diện tích',
        'flag'        => 'admin::range_acreages.index',
        'parent_flag' => 'core.estates'
    ],
    [
        'name'        => 'Thêm mới',
        'flag'        => 'admin::range_acreages.create',
        'parent_flag' => 'admin::range_acreages.index'
    ],
    [
        'name'        => 'Chỉnh sửa',
        'flag'        => 'admin::range_acreages.edit',
        'parent_flag' => 'admin::range_acreages.index'
    ],
    [
        'name'        => 'Xóa',
        'flag'        => 'admin::range_acreages.destroy',
        'parent_flag' => 'admin::range_acreages.index'
    ],
    [
        'name'        => 'Liên hệ',
        'flag'        => 'admin::contacts.index'
    ],
    [
        'name'        => 'Thêm mới',
        'flag'        => 'admin::contacts.create',
        'parent_flag' => 'admin::contacts.index'
    ],
    [
        'name'        => 'Chỉnh sửa',
        'flag'        => 'admin::contacts.edit',
        'parent_flag' => 'admin::contacts.index'
    ],
    [
        'name'        => 'Xóa',
        'flag'        => 'admin::contacts.destroy',
        'parent_flag' => 'admin::contacts.index'
    ],
    [
        'name'        => 'Quận / Huyện',
        'flag'        => 'admin::districts.index',
        'parent_flag' => 'core.estates'
    ],
    [
        'name'        => 'Thêm mới',
        'flag'        => 'admin::districts.create',
        'parent_flag' => 'admin::districts.index'
    ],
    [
        'name'        => 'Chỉnh sửa',
        'flag'        => 'admin::districts.edit',
        'parent_flag' => 'admin::districts.index'
    ],
    [
        'name'        => 'Xóa',
        'flag'        => 'admin::districts.destroy',
        'parent_flag' => 'admin::districts.index'
    ],
    [
        'name'        => 'Đường/Phố',
        'flag'        => 'admin::streets.index',
        'parent_flag' => 'core.estates'
    ],
    [
        'name'        => 'Thêm mới',
        'flag'        => 'admin::streets.create',
        'parent_flag' => 'admin::streets.index'
    ],
    [
        'name'        => 'Chỉnh sửa',
        'flag'        => 'admin::streets.edit',
        'parent_flag' => 'admin::streets.index'
    ],
    [
        'name'        => 'Xóa',
        'flag'        => 'admin::streets.destroy',
        'parent_flag' => 'admin::streets.index'
    ],
    [
        'name'        => 'Setting',
        'flag'        => 'admin::settings.index'
    ],
    [
        'name'          => 'Create',
        'flag'          => 'admin::settings.create',
        'parent_flag'   => 'admin::settings.index'
    ],
    [
        'name'          => 'Edit',
        'flag'          => 'admin::settings.edit',
        'parent_flag'   => 'admin::settings.index'
    ],
    [
        'name'          => 'Delete',
        'flag'          => 'admin::settings.destroy',
        'parent_flag'   => 'admin::settings.index'
    ],
    [
        'name'          => 'Ký gửi nhà đất',
        'flag'          => 'admin::messages.index'
    ],
    [
        'name'          => 'Thêm mới',
        'flag'          => 'admin::messages.create',
        'parent_flag'   => 'admin::messages.index'
    ],
    [
        'name'          => 'Chỉnh sửa',
        'flag'          => 'admin::messages.edit',
        'parent_flag'   => 'admin::messages.index'
    ],
    [
        'name'          => 'Xóa',
        'flag'          => 'admin::messages.destroy',
        'parent_flag'   => 'admin::messages.index'
    ],
    [
        'name'        => 'Công cụ tin tức',
        'flag'        => 'admin::tools.index',
    ]
];
