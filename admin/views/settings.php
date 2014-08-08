
<div class="wrap">
            <h2>Configuration</h2>
            <form method="post" action="options-general.php?">
                <?php settings_fields( FullPeace_Media_To_Post::get_slug( 'settings' ) ); ?>
                <?php settings_fields( FullPeace_Media_To_Post::get_slug( 'option_group' ) ); ?>
                <?php do_settings_sections( 'main_settings'  ); ?>
                <?php do_settings_sections( 'settings_section' ); ?>
                <?php submit_button( __('Save', FPMTP__I18N_NAMESPACE ) ); ?>
<!--<h3>Settings for the Media To Post plugin</h3>
<p>Enable parsing files uploaded via the media library.</p>
<table class="form-table">
    <tr valign="top">
        <th scope="row"><label for="<?php /*FullPeace_Media_To_Post::the_slug('enable_cpt_audio'); */?>"">Enable parsing audio files:</label></th>
        <td><input type="checkbox" id="<?php /*echo FullPeace_Media_To_Post::the_slug('enable_cpt_audio'); */?>" name="<?php /*echo FullPeace_Media_To_Post::the_slug('enable_cpt_audio'); */?>" <?php /*echo self::setting('enable_cpt_ebook') == 'enable' ? "checked" : "" ; */?> value="enable" />
            <a href="<?php /*echo admin_url('edit.php?post_type='.FullPeace_Media_To_Post::$slug.'_audio'); */?>">Talks</a></td>
    </tr>
    <tr valign="top">
        <th scope="row"><label for="<?php /*echo FullPeace_Media_To_Post::the_slug('enable_cpt_video'); */?>">Enable parsing video files:</label> (NOT YET IMPLEMENTED)</th>
        <td><input type="checkbox" id="<?php /*echo FullPeace_Media_To_Post::the_slug('enable_cpt_video'); */?>" name="<?php /*echo FullPeace_Media_To_Post::the_slug('enable_cpt_video'); */?>" <?php /*echo self::setting('enable_cpt_video') == 'enable' ? "checked" : "" ; */?> value="enable" /> <a href="<?php /*echo admin_url('edit.php?post_type='.FullPeace_Media_To_Post::$slug.'_video'); */?>">Video</a></td>
    </tr>
    <tr valign="top">
        <th scope="row"><label for="<?php /*echo FullPeace_Media_To_Post::the_slug('enable_cpt_ebook'); */?>">Enable eBooks (PDF, EPUB, MOBI files):</label> (NOT YET IMPLEMENTED)</th>
        <td><input type="checkbox" id="<?php /*echo FullPeace_Media_To_Post::the_slug('enable_cpt_ebook'); */?>" name="<?php /*echo FullPeace_Media_To_Post::the_slug('enable_cpt_ebook'); */?>" <?php /*echo self::setting('enable_cpt_ebook') == 'enable' ? "checked" : "" ; */?> value="enable" /> <a href="<?php /*echo admin_url('edit.php?post_type='.FullPeace_Media_To_Post::$slug.'_ebook'); */?>">eBook</a></td>
    </tr>
</table>-->
<!--
                <p><strong>RECOMMENDED:</strong> <em>Please do not these settings unless you are absolutely sure of what you are doing.
                        Changing these values require significant operations for existing posts.</em></p>
                <p>If there are no posts generated from media on this site, or you don't care about the existing posts
                (they risk being 'orphaned'), then you can change these settings to the names you want.</p>
                <p><strong>AS OF VERSION 0.1.0, THIS FEATURE IS DISABLED.</strong></p>
                <table class="form-table">
                    <tr valign="top">
                        <th scope="row"><label for="<?php echo FullPeace_Media_To_Post::the_slug('set_cpt_audio'); ?>">Custom Post Type for audio files:</label></th>
                        <td><input type="text" id="<?php echo FullPeace_Media_To_Post::the_slug('set_cpt_audio'); ?>" name="<?php echo FullPeace_Media_To_Post::the_slug('set_cpt_audio'); ?>" value="<?php echo get_option(FullPeace_Media_To_Post::get_slug('set_cpt_audio')); ?>" /></td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><label for="<?php echo FullPeace_Media_To_Post::the_slug('set_cpt_video'); ?>">Custom Post Type for audio files:</label></th>
                        <td><input type="text" id="<?php echo FullPeace_Media_To_Post::the_slug('set_cpt_video'); ?>" name="<?php echo FullPeace_Media_To_Post::the_slug('set_cpt_video'); ?>" value="<?php echo get_option(FullPeace_Media_To_Post::get_slug('set_cpt_video')); ?>" /></td>
                    </tr>
                </table>
                -->
<?php //submit_button(); ?>
</form>
</div>