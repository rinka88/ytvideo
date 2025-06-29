<?php
/*
 * @package     Joomla.Plugin
 * @subpackage  Editors-xtd.ytvideobtn
 * @copyright   Copyright (C) Aleksey A. Morozov. All rights reserved.
 * @license     GNU General Public License version 3 or later; see http://www.gnu.org/licenses/gpl-3.0.txt
 */

namespace AlekVolsk\Plugin\EditorsXtd\YtVideobtn;

\defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\DataObject\DataObject;
use Joomla\CMS\Plugin\CMSPlugin;
use Joomla\CMS\Plugin\PluginHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Filesystem\Path;
// use Joomla\CMS\Version; // Not strictly needed if we simplify template loading

class YtVideobtn extends CMSPlugin
{
    protected $autoloadLanguage = true;

    /**
     * Event method that returns the button.
     *
     * @param   string      $name    The name of the editor area.
     * @param   \Joomla\CMS\Editor\Editor|null  $editor  The editor object, null if not available.
     *
     * @return  \stdClass|null  A button object or null if not enabled.
     *
     * @since   1.0.0
     */
    public function onDisplay(string $name, $editor = null): ?\stdClass
    {
        // $this->_name is the plugin element, e.g., 'ytvideobtn'
        // $this->params are the plugin parameters

        // Ensure the plugin is enabled
        if (!$this->params->get('enabled', 1)) {
             // It seems this plugin doesn't have specific params in its XML for enabled state,
             // so we rely on Joomla's general plugin enabled state.
             // This check might be redundant if Joomla doesn't call onDisplay for disabled plugins.
        }

        // Correct way to get the layout path for the plugin's own type and element
        $layout = new \Joomla\CMS\Layout\FileLayout(
            'plg_editors-xtd_ytvideobtn.default',
            PluginHelper::getLayoutPath('editors-xtd', $this->_name, 'default') // Base path for discovery
        );
        // Get the actual string path for file_exists and include
        $layoutPath = Path::clean(PluginHelper::getLayoutPath('editors-xtd', $this->_name, 'default'));

        if (file_exists($layoutPath)) {
            // This approach relies on the layout file (default.php) echoing the modal HTML.
            // The modal HTML will then be part of the document structure where the editor button is rendered.
            // The $name variable (editor field ID) is passed to the layout.

            // Add specific styles for the modal if not handled by a global CSS file via WebAssetManager
            $this->app->getDocument()->addStyleDeclaration(
                '#ytvideo-modal.modal { top:50%; left:50%; width:600px; max-width:98%; margin-left:0; transform:translate(-50%,-50%); }' .
                '#ytvideo-modal .modal-body { box-sizing:border-box; padding:15px 30px 15px 15px; }'
            );

            // Prepare data for the layout (though default.php currently doesn't explicitly use $data, it uses $name from scope)
            // $data = ['editorName' => $name];

            // The layout tmpl/default.php now ONLY contains the raw HTML for the modal.
            // We will inject this HTML using JavaScript below to ensure it's in the body.
            // And the JS to control it will also be added below.

            // Get the modal HTML content from the layout file.
            // ob_start();
            // include $layoutPath; // $name is available in the scope of default.php
            // $modalHtmlString = ob_get_clean();

            // JavaScript to handle modal HTML injection and event binding
            // $editorIdJs = json_encode($name);
            // $modalHtmlJsString = json_encode($modalHtmlString);

            // $js = "
            // document.addEventListener('DOMContentLoaded', function() {
            //     // Ensure modal HTML is in the document body
            //     if (!document.getElementById('ytvideo-modal')) {
            //         document.body.insertAdjacentHTML('beforeend', {$modalHtmlJsString});
            //     }

            //     const editorIdForModal = {$editorIdJs};
            //     const ytModalElement = document.getElementById('ytvideo-modal');
            //     const ytVideoInsertButton = document.getElementById('ytvideo_insert_button');

            //     function urlcheckYtvideo(url) {
            //         return url.startsWith('http://') || url.startsWith('https://');
            //     }

            //     if (ytVideoInsertButton && ytModalElement) {
            //         ytVideoInsertButton.addEventListener('click', function() {
            //             const url = document.getElementById('ytvideo_url_field').value.trim();
            //             const ratio = document.getElementById('ytvideo_ratio_field').value;
            //             const title = document.getElementById('ytvideo_title_field').value.trim();

            //             if (url !== '' && urlcheckYtvideo(url)) {
            //                 let insertText = '{ytvideo ' + url + '|' + ratio;
            //                 if (title !== '') {
            //                     insertText += '|' + title;
            //                 }
            //                 insertText += '}';

            //                 if (Joomla.editors && Joomla.editors.instances && Joomla.editors.instances[editorIdForModal]) {
            //                     Joomla.editors.instances[editorIdForModal].replaceSelection(insertText);
            //                 } else {
            //                     console.error('YTVIDEOBTN: Joomla editor instance not found for ID: ' + editorIdForModal);
            //                     alert(" . json_encode(Text::_('PLG_EDITORS-XTD_YTVIDEOBTN_ALERT_EDITOR_NOT_FOUND')) . ");
            //                 }
            //             } else {
            //                 alert(" . json_encode(Text::_('PLG_EDITORS-XTD_YTVIDEOBTN_ALERT_INVALID_URL')) . ");
            //             }

            //             const ytVideoModalInstance = bootstrap.Modal.getInstance(ytModalElement);
            //             if (ytVideoModalInstance) {
            //                 ytVideoModalInstance.hide();
            //             }
            //         });
            //     } else {
            //         if (!ytVideoInsertButton) console.error('YTVIDEOBTN: Insert button #ytvideo_insert_button not found.');
            //         if (!ytModalElement) console.error('YTVIDEOBTN: Modal element #ytvideo-modal not found after attempting to add it.');
            //     }
            // });
            // ";
            // $this->app->getDocument()->addScriptDeclaration($js);

            $button = new DataObject();
            // $button->set('text', Text::_('PLG_EDITORS-XTD_YTVIDEOBTN_BUTTON_TEXT'));
            $button->set('text', 'Test YT Button'); // Basic text
            $button->set('name', 'ytvideo'); // Icon name or CSS class
            $button->set('class', 'btn btn-danger'); // Original class
            $button->set('iconSVG', '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 68 48" width="32" height="17"><path d="M66.52,7.74c-0.78-2.93-2.49-5.41-5.42-6.19C55.79,.13,34,0,34,0S12.21,.13,6.9,1.55 C3.97,2.33,2.27,4.81,1.48,7.74C0.06,13.05,0,24,0,24s0.06,10.95,1.48,16.26c0.78,2.93,2.49-5.41,5.42-6.19 C12.21,47.87,34,48,34,48s21.79-0.13,27.1-1.55c2.93-0.78,4.64-3.26,5.42-6.19C67.94,34.95,68,24,68,24S67.94,13.05,66.52,7.74z" fill="#f00"></path><path d="M 45,24 27,14 27,34" fill="#fff"></path></svg>');

            // For Bootstrap 5 modal
            $button->set('modal', false); // Important: set to false when using data attributes for BS5
            $button->set('data-bs-toggle', 'modal');
            $button->set('data-bs-target', '#ytvideo-modal'); // Ensure modal in default.php has id="ytvideo-modal"
            // $button->set('link', '#'); // Not needed for BS5 modal trigger by data attributes
            $button->set('onclick', ''); // Clear if any, not needed for BS5 data attributes

            return $button;
        } else {
            $this->app->enqueueMessage(Text::sprintf('JLIB_ERROR_LAYOUT_NOT_FOUND', $layoutPath), 'error');
            return null;
        }
    }
}
