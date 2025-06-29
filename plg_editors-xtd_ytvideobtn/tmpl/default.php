<?php defined('_JEXEC') or die;
/*
 * @package     Joomla.Plugin
 * @subpackage  Editors-xtd.ytvideo
 * @copyright   Copyright (C) Aleksey A. Morozov. All rights reserved.
 * @license     GNU General Public License version 3 or later; see http://www.gnu.org/licenses/gpl-3.0.txt
 */

use Joomla\CMS\Language\Text;

// $name is the editor ID, available from the including scope of ytvideobtn.php's onDisplay method
// $this is the plugin instance, also available.
$editorFieldName = $name;
?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Modal HTML structure as a string
    // Ensure IDs are unique if multiple editors are on the page, though this button usually appears once per editor type.
    // However, standard practice is one modal instance in the document.
    const ytvideoModalHtml = `
<div id="ytvideo-modal" class="joomla-modal modal fade" role="dialog" aria-labelledby="ytvideo-modal-title" aria-hidden="true" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ytvideo-modal-title"><?php echo Text::_('PLG_EDITORS-XTD_YTVIDEOBTN_MODAL_TITLE'); ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="<?php echo Text::_('JLIB_HTML_BEHAVIOR_CLOSE'); ?>"></button>
            </div>
            <div class="modal-body">
                <div class="form-vertical">
                    <div class="mb-3">
                        <label for="ytvideo_url_field" class="form-label"><?php echo Text::_('PLG_EDITORS-XTD_YTVIDEOBTN_LABEL_URL'); ?></label>
                        <input name="ytvideourl" id="ytvideo_url_field" value="" class="form-control" type="text">
                    </div>
                    <div class="mb-3">
                        <label for="ytvideo_ratio_field" class="form-label"><?php echo Text::_('PLG_EDITORS-XTD_YTVIDEOBTN_LABEL_RATIO'); ?></label>
                        <select name="ytvideoratio" id="ytvideo_ratio_field" class="form-select">
                            <option value="4-3">4:3 (TV)</option>
                            <option value="5-3">5:3 (Wide TV)</option>
                            <option value="16-9" selected>16:9 (Standard YouTube, HD)</option>
                            <option value="167-9">16.7:9 (Standard films)</option>
                            <option value="18-9">18:9 (iPhone)</option>
                            <option value="199-9">19.9:9 (Wide 70mm)</option>
                            <option value="235-1">2.35:1 (Panavision)</option>
                            <option value="255-1">2.55:1 (Cinemascope)</option>
                            <option value="27-1">2.7:1 (Ultra Panavision, 2K/4K)</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="ytvideo_title_field" class="form-label"><?php echo Text::_('PLG_EDITORS-XTD_YTVIDEOBTN_LABEL_TITLE'); ?></label>
                        <input name="ytvideotitle" id="ytvideo_title_field" value="" class="form-control" type="text">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="ytvideo_insert_button"><?php echo Text::_('PLG_EDITORS-XTD_YTVIDEOBTN_MODAL_BTN_INSERT'); ?></button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?php echo Text::_('PLG_EDITORS-XTD_YTVIDEOBTN_MODAL_BTN_CANCEL'); ?></button>
            </div>
        </div>
    </div>
</div>`;

    // Insert the modal HTML into the body if not already there.
    // This ensures it's available. The main plugin file (ytvideobtn.php) uses addCustomTag for the output of this layout,
    // so this script block itself will be part of that output.
    if (!document.getElementById('ytvideo-modal')) {
        document.body.insertAdjacentHTML('beforeend', ytvideoModalHtml);
    }

    const editorIdForModal = <?php echo json_encode($editorFieldName); ?>;
    const ytModalElement = document.getElementById('ytvideo-modal'); // Should exist now
    const ytVideoInsertButton = document.getElementById('ytvideo_insert_button');

    function urlcheckYtvideo(url) {
        // Basic check, can be improved
        return url.startsWith('http://') || url.startsWith('https://');
    }

    if (ytVideoInsertButton && ytModalElement) {
        ytVideoInsertButton.addEventListener('click', function() {
            const url = document.getElementById('ytvideo_url_field').value.trim();
            const ratio = document.getElementById('ytvideo_ratio_field').value;
            const title = document.getElementById('ytvideo_title_field').value.trim();

            if (url !== '' && urlcheckYtvideo(url)) {
                let insertText = '{ytvideo ' + url + '|' + ratio;
                if (title !== '') {
                    insertText += '|' + title;
                }
                insertText += '}';

                if (Joomla.editors && Joomla.editors.instances && Joomla.editors.instances[editorIdForModal]) {
                    Joomla.editors.instances[editorIdForModal].replaceSelection(insertText);
                } else {
                    console.error('Joomla editor instance not found for ID: ' + editorIdForModal);
                    alert('<?php echo Text::_('PLG_EDITORS-XTD_YTVIDEOBTN_ALERT_EDITOR_NOT_FOUND', true); ?>');
                }
            } else {
                alert('<?php echo Text::_('PLG_EDITORS-XTD_YTVIDEOBTN_ALERT_INVALID_URL', true); ?>');
            }

            // document.getElementById('ytvideo_url_field').value = ''; // Optionally clear fields
            // document.getElementById('ytvideo_title_field').value = '';

            const ytVideoModalInstance = bootstrap.Modal.getInstance(ytModalElement);
            if (ytVideoModalInstance) {
                ytVideoModalInstance.hide();
            }
        });
    } else {
        if (!ytVideoInsertButton) console.error('YTVIDEOBTN: Insert button not found.');
        if (!ytModalElement) console.error('YTVIDEOBTN: Modal element not found after attempting to add it.');
    }
});
</script>
