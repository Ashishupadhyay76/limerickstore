<?php

return [
  'tooltips' => [
    'watermark-show' => __('Protect your photos with watermarks', 'sgg'),
    'watermark-save-source-images' => __('The original images without watermarks will be saved on your server', 'sgg'),
    'disable-right-click' => __('Disable right click on hover over the images in the gallery', 'sgg'),
    'disable-source-image-for-linked-images' => __('Check if you want to hide source image for Linked images in popup', 'sgg'),
    'load-with-scroll' => __('Enable loading of images with scrolling the page', 'sgg'),
    'loading-text' => __('Specify the text that your users will see during images loading', 'sgg'),
    'images-amount' => __('Define the quantity of images that will be shown', 'sgg'),
    'load-more-amount' => __('Define the quantity of images that will be loaded when you press the button', 'sgg'),

    'disable-title-optimize' => __('Disable using of capital letters for every word in title of categories.', 'sgg'),
    'browserUrlTooltipHide' => __('If this option is enabled, links to images will not be displayed in your browser. Also it will be impossible to open gallery in popup. In case you are not sure, please do not enable the option.', 'sgg'),
    'recollectBtnHelper' => __('Recollect EXIF data for old galleries, which were uploaded without importing of EXIF metadata.', 'sgg'),
    'mosaic-display-all-images' => __('If this option is enabled, all pictures in gallery will be shown as thumbnails with no count.', 'sgg'),
    'mosaic-count-columns' => __('Set the value from 3 to 20. Gallery area can contain up to this amount of minimal-sized pictures. The size of a single photo can vary in range from (Gallery width/Number of columns) to 70% of the Gallery width.', 'sgg'),
    'img-count-header' => __('Image count options. If none of the options below is enabled, your gallery images will be partially hidden under thumbnail with count. After clicking on this thumbnail, rest of the images will be shown in pop-up window.', 'sgg'),
    'mosaic-img-count-settings' => __('Here you may change the image counting thumbnail view.', 'sgg'),
    'mics-font-family' => __('Here you may choose the font family for your image counting thumbnail.', 'sgg'),
    'mics-font-size' => __('Here you may choose the font size for your image counting thumbnail.', 'sgg'),
    'mics-text-color' => __('Here you may choose the font color for your image counting thumbnail', 'sgg'),
    'mics-font-style' => __('Make your image counting thumbnail font bold, italic or underlined.', 'sgg'),
    'mics-background-color' => __('Choose the background color for image counting thumbnaill.', 'sgg'),
    'mics-background-transparency' => __('Choose the background transparency for counter thumbnail.', 'sgg'),
    // custom buttons
    'custom-buttons' => __('Here you may find a great number of presets to customize your gallery buttons. ', 'sgg') . '<a target=\'_blank\' href=\'https://supsystic.com/documentation/custom-buttons/\'>https://supsystic.com/documentation/custom-buttons/</a>',
    'tcb-cust-class' => __('Here you may add custom CSS classes for your gallery. ', 'sgg'),
    'tcb-text-color' => __('Select a text color for your button. ', 'sgg'),
    'tcb-background-color' => __('Select the color of your button. ', 'sgg'),
    'tcb-font-family' => __('Select font family for your button text. ', 'sgg'),
    'tcb-font-size' => __('Select font size for your button text. ', 'sgg'),
    'tcb-font-weight' => __('Select font weight for your button text (may be normal or bold). ', 'sgg'),
    'tcb-vertical-padding' => __('Set vertical padding for your button in pixels. Note: this parameter affects your button\'s height. ', 'sgg'),
    'tcb-horizontal-padding' => __('Set horizontal padding for your button in pixels. Note: this parameter affects your button\'s width. ', 'sgg'),
    'tcb-border-style' => __('Select border style for your button. ', 'sgg'),
    'tcb-border-width' => __('Set border thickness in pixels. ', 'sgg'),
    'tcb-border-color' => __('Choose the color for your button border. ', 'sgg'),
    'tcb-border-radius' => __('Set the radius of corner rounding for your button in pixels. ', 'sgg'),
    // popup
    'sgg-t-popup-linked-image-txt' => __(
      'Here you may choose the type of the text, which appears under your linked images after opening them in pop-up. You may add this text when you choose the linked images from your site media library. Empty text fields will appear at the right side of the page after selecting the image.',
      'sgg',
    ),
    'disable-source-image-for-linked-images' => __('If this option is enabled, after clicking on source image thumbnail, only linked images will appear in pop-up box. The source image itself will not be displayed.', 'sgg'),
    'enable-download-photos-button' => __('If this option is enabled, the Download button will appear in pop-up box, which will allow you to download the original image.', 'sgg'),
    'video.size' => __('Set video size in pop-up box (width x height).', 'sgg'),
    'video.autoplay' => __('If this option is enabled, video playback starts automatically as soon as pop-up box appears.', 'sgg'),
    'video.onEnd' => __('Here you can select, what happens when video is over.', 'sgg'),
    // Gallery Loader
    'preload-background' => __('Choose background color for gallery pre-load window.', 'sgg'),
    'preloadIconPlace' => __('Choose icon to indicate that the gallery is loading.', 'sgg'),
    // Custom Attributes
    'attributes' => __('Enable and customize custom attributes for filtering and searching.', 'sgg'),
    'manageAttributes' => __('Here you can specify the names and order of displaying the attributes. After this all images in gallery will have all that attributes and you will be able to set the attribute values on Images List.', 'sgg'),
    'showAttributes' => __('This option adds an Attributes Panel to the pop-up window. All custom attributes will be displayed in this panel. Note: this option works only with Popup theme #6.', 'sgg'),
    'attributesPosition' => __('Here you can set the position of the Attributes Panel in the pop-up window.', 'sgg'),
    'attributesWidth' => __('Set the width of the Attributes Panel.', 'sgg'),
    'showButtonLink' => __('This option adds an Button with Link to the Attributes Panel.', 'sgg'),
    'buttonLinkTitle' => __('Set Title for the Button with Link in Attributes Panel. This Title will be shown for all images in this gallery. In case you want to show different titles, set them for each image separately on Images List.', 'sgg'),
    'buttonLinkUrl' => __(
      'Here you may add the link, which opens when clicking on your Button in Attributes Panel. This link will be opened for all images in this gallery. In case you want to open different links, set them for each image separately on Images List in the Attributes section.',
      'sgg',
    ),
    'showButtonLink' => __('This option adds an Button with Link to the Attributes Panel in the pop-up window.', 'sgg'),
    'showFilter' => __('This option allows you to filter gallery images by Custom Attributes values. Note: this option does not work with Categories and LoadMore Options.', 'sgg'),
    'showSearch' => __('This option allows you to enable the search for images by Keywords, which you can set for each image separately on Image List in the Attributes section. Note: this option does not work with Categories and LoadMore Options.', 'sgg'),
    // Thumbnail transformations
    'captionEffSettEnableTd2' =>
      __('This option allows you to enable different animated effects for your gallery image thumbnails. The thumbnails will transform, when the mouse cursor is over them. Note: this option conflicts with some effects (see the list). ', 'sgg') .
      '<a target=\'_blank\' href=\'https://supsystic.com/documentation/thumbnail-transformations/\'>https://supsystic.com/documentation/thumbnail-transformations/</a>',
    'sgg-ttt-grow-srink' => __('This option makes your thumbnail grow or shrink when mouse cursor is over it. You may set value in percents.', 'sgg'),
    'sgg-ttt-opacity-fade' => __('This option enables opacity fade effect when mouse cursor is over the thumbnail. You may set value in percents.', 'sgg'),
    'sgg-ttt-square-to-circle' => __('This option transforms your thumbnail from square to circle when mouse cursor is over it. You may set transformation value in percents.', 'sgg'),
    'sgg-ttt-rotate' => __('This option makes your thumbnail rotate. You may set the rotation degree in percents.', 'sgg'),
    // Caption Builder
    'useCaptionBuilder' =>
      __('Caption builder enables wide range of options and detailed caption settings. Note: you will not be able to display descriptions of your images if Caption builder will be disabled. ', 'sgg') .
      '<a target=\'_blank\' href=\'https://supsystic.com/blog/caption-builder-of-photo-gallery/\'>https://supsystic.com/blog/caption-builder-of-photo-gallery/</a>',
    'captionBuilderBackgroundEnableRow' => __('Activate caption background settings. ', 'sgg') . '<a target=\'_blank\' href=\'https://supsystic.com/documentation/caption-builder/\'>https://supsystic.com/documentation/caption-builder/</a>',
    'overlayBackgroundRow' => __('Choose backround color for captions. ', 'sgg') . '<a target=\'_blank\' href=\'https://supsystic.com/documentation/caption-builder/\'>https://supsystic.com/documentation/caption-builder/</a>',
    'captb-bg-transparency' => __('Choose backround transparency for captions. ', 'sgg') . '<a target=\'_blank\' href=\'https://supsystic.com/documentation/caption-builder/\'>https://supsystic.com/documentation/caption-builder/</a>',
    'captionBuilderChooseEffectRow' => __('Make your captions more attractive with animation effect. ', 'sgg') . '<a target=\'_blank\' href=\'https://supsystic.com/documentation/caption-builder/\'>https://supsystic.com/documentation/caption-builder/</a>',
    'overlay-effect-image-on-hover-enable' =>
      __(
        '\'Image on hover\' - If this effect is enabled, the other image will be shown on the place of the current one, when mouse cursor is over it. The other image could be selected in the option below (\'Select Image on hover\') and will be shown for all images in this gallery. In case you want to show different pictures, upload them for each image separately on Images List section (\'Hover Caption Image\' tab). Please note, that \'Personal Captions\' option should be enabled. ',
        'sgg',
      ) . '<a target=\'_blank\' href=\'https://supsystic.com/documentation/caption-builder/\'>https://supsystic.com/documentation/caption-builder/</a>',
    'overlay-effect-image-on-hover' =>
      __('You may upload an image from Wordpress media library. This image will appear while hovering over any gallery thumbnail. ', 'sgg') . '<a target=\'_blank\' href=\'https://supsystic.com/example/two-side-images-gallery/\'>https://supsystic.com/example/two-side-images-gallery/</a>',
    'overlay-personal' =>
      __(
        'If this option is enabled, a \'Choose effect\' button appears on the right of every caption entry field on the \'Image list\' page. You may press the button and choose personal caption effect per image in images list. If option is disabled, main chosen effect will be used for all images. ',
        'sgg',
      ) . '<a target=\'_blank\' href=\'https://supsystic.com/documentation/caption-builder/\'>https://supsystic.com/documentation/caption-builder/</a>',
    'captionBuilderPolaroidEffectRow' =>
      __(
        'If this option is enabled, your gallery image thumbnails will be displayed in Polaroid style frames. Images will be cropped to a square shape. Captions will apperar below the thumbnails when hovering mouse over it. Please note that Polaroid Style will not work for Gallary Type Mosaic in multi-column mode.',
        'sgg',
      ) . ' <a target=\'_blank\' href=\'https://supsystic.com/documentation/polaroid-gallery/\'>https://supsystic.com/documentation/polaroid-gallery/</a>',
    'captionBuilderPolaroidAnimationRow' =>
      __('If this option is enabled, your polaroid image thumbnails move when you hover mouse cursor over them. ', 'sgg') . '<a target=\'_blank\' href=\'https://supsystic.com/documentation/polaroid-gallery/\'>https://supsystic.com/documentation/polaroid-gallery/</a>',
    'captionBuilderPolaroidScatteringRow' => __('Choose this option to make your polaroid image thumbnails scatter in a random way. ', 'sgg') . '<a target=\'_blank\' href=\'https://supsystic.com/documentation/polaroid-gallery/\'>https://supsystic.com/documentation/polaroid-gallery/</a>',
    'captionBuilderPolaroidFrameWidthRow' =>
      __('Set polaroid frame padding in pixels. Note: increasing this parameter may make your thumbnail images smaller. ', 'sgg') . '<a target=\'_blank\' href=\'https://supsystic.com/documentation/polaroid-gallery/\'>https://supsystic.com/documentation/polaroid-gallery/</a>',
    'captionBuilderTitleEnableRow' => __(
      'If this option is enabled, captions are displayed  when mouse cursor is over the thumbnail. You may also find detailed caption settings below. Note: if Caption, Description and Icons options are enabled at the same time, they may partially overlay each other.',
      'sgg',
    ),
    'captb-title-height' => __('Here you may choose the caption height in pixels or percents (for example: 33%, 50px).', 'sgg'),
    'captb-title-position' => __('Here you may choose the caption text position.', 'sgg'),
    'captb-title-padding' => __('Here you may set the padding values for the caption text.', 'sgg'),
    'thumbnailOverlayFontFamilyRow' => __('Select font for your caption text.', 'sgg'),
    'catpb-title-font-size' => __('Choose font size for your caption text.', 'sgg'),
    'thumbnailOverlayFontColorRow' => __('Choose your caption text color.', 'sgg'),
    'captb-title-font-style' => __('Make your caption font bold, italic or underlined.', 'sgg'),
    'captionBuilderTitleBgColorRow' => __('Choose color of the highlight behind your caption text.', 'sgg'),
    'captionBuilderTitleBgColorTransparencyRow' => __('Set the transparency of the highlight behind your caption text.', 'sgg'),
    'captionBuilderDescriptionEnableRow' => __(
      'If this option is enabled, descriptions are displayed  when mouse cursor is over the thumbnail. You may also find detailed description settings below. Note: if Caption, Description and Icons options are enabled at the same time, they may partially overlay each other.',
      'sgg',
    ),
    'captb-descr-height' => __('Here you may choose the description height in pixels or percents (for example: 33%, 50px).', 'sgg'),
    'captb-descr-position' => __('Here you may choose the description text position.', 'sgg'),
    'captb-descr-paddings' => __('Here you may set the padding values for the description text.', 'sgg'),
    'captionBuilderDescriptionFontFamilyRow' => __('Select font for your description text.', 'sgg'),
    'captb-descr-font-size' => __('Choose font size for your description text.', 'sgg'),
    'captBuildDescriptionFontColorRow' => __('Choose your description text color.', 'sgg'),
    'captb-descr-font-style' => __('Make your description text bold, italic or underlined.', 'sgg'),
    'captBuildDescriptionBgColorRow' => __('Choose color of the highlight behind your description text.', 'sgg'),
    'captBuildDescriptionBgColorTransparencyRow' => __('Set the transparency of the highlight behind your description text.', 'sgg'),
    'iconsEnable' =>
      __(
        'If this option is enabled, icons are displayed when mouse cursor is over the thumbnail. Icons make your gallery interactive and allow your site visitors to take different actions with images. Note: if Caption, Description and Icons options are enabled at the same time, they may partially overlay each other. ',
        'sgg',
      ) . '<a target=\'_blank\' href=\'https://supsystic.com/documentation/icons/\'>https://supsystic.com/documentation/icons/</a>',
    'captb-icons-height' => __('Here you may choose the icons container height in pixels or percents (for example: 33%, 50px).', 'sgg'),
    'captb-icons-position' => __('Here you may choose the icons position.', 'sgg'),
    'captb-icons-paddings' => __('Here you may set the padding values for the icons.', 'sgg'),
    'iconsPopupIcon' => __('Choose your pop-up image icon symbol.', 'sgg'),
    'iconsVideoIcon' => __('Choose your video icon symbol.', 'sgg'),
    'iconsLinkIcon' => __('Choose your link icon symbol.', 'sgg'),
    'iconsDownloadIcon' => __('Choose your download icon symbol.', 'sgg'),
    'captb-icons-font-size' => __('Here you may set icons size in pixels.', 'sgg'),
    'captb-icons-color' => __('Choose the color for your icon symbols.', 'sgg'),
    'captb-icons-hover-color' => __('Choose the color for your icon symbols while hovering over them.', 'sgg'),
    'iconsTransparencyRow' => __('Choose the icon symbols color transparency.', 'sgg'),
    'captb-icons-bg-color' => __('Choose the color for your icon backgrounds.', 'sgg'),
    'captb-icons-hover-bg-color' => __('Choose the color for your icon backgrounds while hovering over them.', 'sgg'),
    'iconsBgTransparencyRow' => __('Choose the icon background color transparency.', 'sgg'),
    'captBuildIconsBgColorRow' => __('Choose color of the highlight behind your icons..', 'sgg'),
    'captBuildIconsBgColorTransparencyRow' => __('Set the transparency of the highlight behind your icons.', 'sgg'),
    'captb-icons-distance-between' => __('Set the distance between icons. Note: you may set this parameter only for several icons.', 'sgg'),
    'captb-icons-animation-effects' => __('Here you may choose the animation effects for your icon symbols. Chosen effect will be the same for all the icons.', 'sgg'),
    // EXIF data
    'captb-exif-enabled' =>
      __('This option helps you display image metadata from Exchangeable Image File Format to give your gallery more professional details. ', 'sgg') . '<a target=\'_blank\' href=\'https://supsystic.com/example/exif-meta-data/\'>https://supsystic.com/example/exif-meta-data/</a>',
    'recollectBtnHelper' => __('If your gallery images were previously uploaded without EXIF metadata and now you want to display it, press the button to recollect metadata for all gallery images.', 'sgg'),
    'show-EXIF-as-caption' => __('If this option is enabled, EXIF data is displayed as caption. Note: in this case the original caption text will be replaced.If both \'Show as caption\' and \'Show description\' options are activated, EXIF data will be displayed twice. ', 'sgg'),
    'show-EXIF-as-description' => __(
      'If this option is enabled, EXIF data is displayed as description. Note: in this case the original description text will be replaced. If both \'Show as caption\' and \'Show description\' options are activated, EXIF data will be displayed twice. This option is not available, if Caption builder is disabled.',
      'sgg',
    ),
    'show-EXIF-on-lightobox-image' => __('If this option is enabled, EXIF data is displayed below images in pop-up window.', 'sgg'),
    'captb-exif-options' => __('Select the parameters which you want to be displayed.', 'sgg'),
    'camera-name' => __('This parameter displays camera brand.', 'sgg'),
    'camera-model' => __('This parameter displays definite camera model.', 'sgg'),
    'aperture' => __('This parameter displays your camera aperture.', 'sgg'),
    'exposure-bias' => __('This parameter displays the exposure bias.', 'sgg'),
    'flash' => __('This parameter displays if your camera flash was active while taking the picture.', 'sgg'),
    'focal-length' => __('This parameter displays the focal length of your camera lens.', 'sgg'),
    'iso' => __('This parameter displays ISO settings of the image.', 'sgg'),
    'shutter-speed' => __('This parameter displays shutter speed settings of the image.', 'sgg'),
    'location' => __('This parameter displays location, where the picture was taken. Note: this data is available only if your camera has GPS-connection. If your camera has no GPS and this data is not available in EXIF, it will not appear.', 'sgg'),
    // Categories
    'sgg-cap-hide-all-cat' =>
      __('This option allows you to hide \'All\' category, which is set by default. In this case images will be arranged only by your own categories list. ', 'sgg') . '<a target=\'_blank\' href=\'https://supsystic.com/documentation/categories/\'>https://supsystic.com/documentation/categories/</a>',
    'enable-shuffling-animation' => __('This option enables shuffling animation effect when the categories are changed. ', 'sgg') . '<a target=\'_blank\' href=\'https://supsystic.com/documentation/categories/\'>https://supsystic.com/documentation/categories/</a>',
    'animation-duration' => __('Here you may set transition/animation speed in milliseconds. ', 'sgg') . '<a target=\'_blank\' href=\'https://supsystic.com/documentation/categories/\'>https://supsystic.com/documentation/categories/</a>',
    'sgg-cap-position' => __('Here you may set categories list position over or under the gallery. ', 'sgg') . '<a target=\'_blank\' href=\'https://supsystic.com/documentation/categories/\'>https://supsystic.com/documentation/categories/</a>',
    'sgg-cap-category-align' => __('Choose your categories list align. ', 'sgg') . '<a target=\'_blank\' href=\'https://supsystic.com/documentation/categories/\'>https://supsystic.com/documentation/categories/</a>',
    'categories-flexible-height' => __('The height of the gallery can vary by switching between categories. If the option is not enabled, the gallery height will not decrease if the number of visible photos is reduced, i.e. bottom may be empty space.', 'sgg'),
    'disable-title-optimize' => __('If this option is enabled, words in category names do not start with capital letters. ', 'sgg') . '<a target=\'_blank\' href=\'https://supsystic.com/documentation/categories/\'>https://supsystic.com/documentation/categories/</a>',
    'sgg-cap-multiselect' => __('If this option is enabled, you will be able to select several categories for the review. ', 'sgg') . '<a target=\'_blank\' href=\'https://supsystic.com/documentation/categories/\'>https://supsystic.com/documentation/categories/</a>',
    'sgg-cap-multiselect-results' => __(
      'Choose the type of categories filtering for Multi-select mode if there are several categories was chosen:<br /><b>At least one selected</b>  - show image if it has ta least one of chosen categories<br /><b>Only all selected</b> - show image only if it has all selected categories',
      'sgg',
    ),
    'sgg-cap-category-order' => __('Drag and drop categories to set their order. ', 'sgg') . '<a target=\'_blank\' href=\'https://supsystic.com/documentation/categories/\'>https://supsystic.com/documentation/categories/</a>',
    // Pagination
    'sgg-cap-images-per-page' => __('Here you may set the quantity of images that will be displayed on one page. ', 'sgg') . '<a target=\'_blank\' href=\'https://supsystic.com/documentation/pagination/\'>https://supsystic.com/documentation/pagination/</a>',
    'sgg-cap-show-prev-nex-arr' => __('This option adds Previous/Next arrows to the buttons with page numbers. ', 'sgg') . '<a target=\'_blank\' href=\'https://supsystic.com/documentation/pagination/\'>https://supsystic.com/documentation/pagination/</a>',
    'sgg-cap-prev-arr-text' => __('Here you may set the custom text for your \'Previous\' arrow. ', 'sgg') . '<a target=\'_blank\' href=\'https://supsystic.com/documentation/pagination/\'>https://supsystic.com/documentation/pagination/</a>',
    'sgg-cap-next-arr-text' => __('Here you may set the custom text for your \'Next\' arrow. ', 'sgg') . '<a target=\'_blank\' href=\'https://supsystic.com/documentation/pagination/\'>https://supsystic.com/documentation/pagination/</a>',
    'sgg-cap-btn-position' => __('Here you may set pagination buttons position over or under the gallery. ', 'sgg') . '<a target=\'_blank\' href=\'https://supsystic.com/documentation/pagination/\'>https://supsystic.com/documentation/pagination/</a>',
    'sgg-cap-pagination-align' => __('Choose your pagination buttons align. ', 'sgg') . '<a target=\'_blank\' href=\'https://supsystic.com/documentation/pagination/\'>https://supsystic.com/documentation/pagination/</a>',
    // posts
    'sgg-posts-layout-enable' => __('This feature enables post layout for your gallery. ', 'sgg') . '<a target=\'_blank\' href=\'https://supsystic.com/documentation/posts/\'>https://supsystic.com/documentation/posts/</a>',
    'sgg-posts-layout-style' => __('Here you may choose the post. ', 'sgg') . '<a target=\'_blank\' href=\'https://supsystic.com/documentation/posts/\'>https://supsystic.com/documentation/posts/</a>',
    'sgg-auto-posts-enable' =>
      __('Auto Posts\' feature offers you to automatically pull content from your site or blog to post feed. Choose the number of posts and post categories and that number of the latest posts will always be displayed in these categories. ', 'sgg') .
      '<a target=\'_blank\' href=\'https://supsystic.com/documentation/posts/\'>https://supsystic.com/documentation/posts/</a>',
    'sgg-auto-posts-number' => __('Here you may set the number of latest posts to be displayed in your post feed gallery. ', 'sgg') . '<a target=\'_blank\' href=\'https://supsystic.com/documentation/posts/\'>https://supsystic.com/documentation/posts/</a>',
    'auto-posts-categories' =>
      __(
        'Here you may choose the post categories to be displayed in your post feed. Note: In order to make this option work properly you need to categorize the posts on your site. To set the post category from the list or add new one go to Categories menu at the bottom right part of \'Edit post\' page and choose one of the categories from the list or add new one. ',
        'sgg',
      ) . '<a target=\'_blank\' href=\'https://supsystic.com/documentation/posts/\'>https://supsystic.com/documentation/posts/</a>',
    'post_author' => __('Enable this option to show author in your post. ', 'sgg') . '<a target=\'_blank\' href=\'https://supsystic.com/documentation/posts/\'>https://supsystic.com/documentation/posts/</a>',
    'posts_date' => __('Enable this option to show your post date. ', 'sgg') . '<a target=\'_blank\' href=\'https://supsystic.com/documentation/posts/\'>https://supsystic.com/documentation/posts/</a>',
    'sgg-posts-show-content' => __('Enable this option to show the beginning of your post content text. ', 'sgg') . '<a target=\'_blank\' href=\'https://supsystic.com/documentation/posts/\'>https://supsystic.com/documentation/posts/</a>',
    'sgg-posts-show-category' =>
      __(
        'Enable this option to show posts categories. Note: In order to make this option work properly you need to categorize the posts on your site. To set the post category from the list or add new one go to Categories menu at the bottom right part of \'Edit post\' page and choose one of the categories from the list or add new one. ',
        'sgg',
      ) . '<a target=\'_blank\' href=\'https://supsystic.com/documentation/posts/\'>https://supsystic.com/documentation/posts/</a>',
    // watermarks
    'watermark-show' => __('This option enables Watermarks. ', 'sgg') . '<a target=\'_blank\' href=\'https://supsystic.com/documentation/gallery-watermark/\'>https://supsystic.com/documentation/gallery-watermark/</a>',
    'watermark-show-for' => __('Here you may choose if you wan to show watermarks on your Thumbnails, Pop-up images or both. ', 'sgg') . '<a target=\'_blank\' href=\'https://supsystic.com/documentation/gallery-watermark/\'>https://supsystic.com/documentation/gallery-watermark/</a>',
    'watermark-img-upload-block' => __('Upload your watermark image. ', 'sgg') . '<a target=\'_blank\' href=\'https://supsystic.com/documentation/gallery-watermark/\'>https://supsystic.com/documentation/gallery-watermark/</a>',
    'watermark-size-thumbnails' => __('Set your watermark size for Thumbnails in percent. ', 'sgg'),
    'watermark-size-popup' => __('Set your watermark size for Popup in percent. ', 'sgg'),
    'watermark-margin' => __('Set your watermark margin value in pixels. ', 'sgg') . '<a target=\'_blank\' href=\'https://supsystic.com/documentation/gallery-watermark/\'>https://supsystic.com/documentation/gallery-watermark/</a>',
    'watermark-posion' => __('Choose your watermark position. ', 'sgg') . '<a target=\'_blank\' href=\'https://supsystic.com/documentation/gallery-watermark/\'>https://supsystic.com/documentation/gallery-watermark/</a>',
    'watermark-rotate' => __('Choose your watermark rotation degree. ', 'sgg') . '<a target=\'_blank\' href=\'https://supsystic.com/documentation/gallery-watermark/\'>https://supsystic.com/documentation/gallery-watermark/</a>',
    'watermark-transparency' => __('Choose your watermark transparency. The default rate is set to 50%. ', 'sgg') . '<a target=\'_blank\' href=\'https://supsystic.com/documentation/gallery-watermark/\'>https://supsystic.com/documentation/gallery-watermark/</a>',
    'watermark-update' => __('Update your watermark on all images in gallery with current settings.', 'sgg'),
  ],
];
