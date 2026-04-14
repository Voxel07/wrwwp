<?php
// Extends standard WordPress User Profiles with specific Team data

function wrw_user_profile_fields($user) {
    ?>
    <h3>Team Mitgliedschaft (Airsoft)</h3>
    <table class="form-table">
        <tr>
            <th><label for="wrw_notification_pref">Benachrichtigungen (Ankündigungen)</label></th>
            <td>
                <select name="wrw_notification_pref" id="wrw_notification_pref">
                    <option value="webhook" <?php selected(get_the_author_meta('wrw_notification_pref', $user->ID), 'webhook'); ?>>Instant Messenger (Webhook)</option>
                    <option value="mail" <?php selected(get_the_author_meta('wrw_notification_pref', $user->ID), 'mail'); ?>>E-Mail</option>
                </select>
                <br><span class="description">Wie sollen Team-Ankündigungen zugeteilt werden?</span>
            </td>
        </tr>
        <tr>
            <th><label for="wrw_ribbon">Ribbon Text</label></th>
            <td>
                <input type="text" name="wrw_ribbon" id="wrw_ribbon" value="<?php echo esc_attr(get_the_author_meta('wrw_ribbon', $user->ID)); ?>" class="regular-text" />
                <br><span class="description">Optional (z.B. Webmaster, Teamältester).</span>
            </td>
        </tr>
        <tr>
            <th><label for="wrw_phrase">Kurzer Spruch</label></th>
            <td>
                <input type="text" name="wrw_phrase" id="wrw_phrase" value="<?php echo esc_attr(get_the_author_meta('wrw_phrase', $user->ID)); ?>" class="regular-text" />
                <br><span class="description">Prägnante Info über das Mitglied.</span>
            </td>
        </tr>
        <tr>
            <th><label for="wrw_joined_date">Beitrittsdatum</label></th>
            <td>
                <input type="date" name="wrw_joined_date" id="wrw_joined_date" value="<?php echo esc_attr(get_the_author_meta('wrw_joined_date', $user->ID)); ?>" />
            </td>
        </tr>
        <tr>
            <th><label for="wrw_birthday">Geburtstag</label></th>
            <td>
                <input type="date" name="wrw_birthday" id="wrw_birthday" value="<?php echo esc_attr(get_the_author_meta('wrw_birthday', $user->ID)); ?>" />
                <br><span class="description">Notwendig für die Altersberechnung und den 🎂.</span>
            </td>
        </tr>
    </table>
    <?php
}
add_action('show_user_profile', 'wrw_user_profile_fields');
add_action('edit_user_profile', 'wrw_user_profile_fields');

function wrw_save_user_profile_fields($user_id) {
    if (!current_user_can('edit_user', $user_id)) {
        return false;
    }
    update_user_meta($user_id, 'wrw_notification_pref', sanitize_text_field($_POST['wrw_notification_pref']));
    update_user_meta($user_id, 'wrw_ribbon', sanitize_text_field($_POST['wrw_ribbon']));
    update_user_meta($user_id, 'wrw_phrase', sanitize_text_field($_POST['wrw_phrase']));
    update_user_meta($user_id, 'wrw_joined_date', sanitize_text_field($_POST['wrw_joined_date']));
    update_user_meta($user_id, 'wrw_birthday', sanitize_text_field($_POST['wrw_birthday']));
}
add_action('personal_options_update', 'wrw_save_user_profile_fields');
add_action('edit_user_profile_update', 'wrw_save_user_profile_fields');
