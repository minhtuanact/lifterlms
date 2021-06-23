<div class="wrap">
    <h2>Website Monetization by MageNet</h2>
    <p>
        By installing WordPress plugin, you allow MageNet to display paid contextual ads on pages where you grant permission via Pages options.<br>
        Ad placement and removal process takes 3 hours. Please be patient.
    </p>
    <hr style="margin-bottom: 20px">
    
    <div class="tool-box">
        <h2 class="title">MageNet Key:</h2>
        <form method="post" action="">
            <input type="text" name="key" value="<?php echo (!empty($magenet_key) ? $magenet_key : ''); ?>" style="width: 325px" /><br>&nbsp;<br>
            <input type="submit" name="submit_key" value="Save" style="width: 110px; margin-right: 12px;" />
            <?php echo (!empty($result_text) ? $result_text : ''); ?>
        </form>
    </div>
    <hr style="margin-bottom: 20px">

    <?php if (!empty($magenet_key) && $magenet_key): ?>
        <div class="tool-box">
            <h2 class="title">Specify the ads location and placement method:</h2>
            <form method="post" action="">
                <?php $ch = 'checked="checked"'; ?>
                <div style="margin-bottom: 4px"><input type="radio" name="links_show_by" value="0" <?php echo $this->plugin_show_by != 1 ? $ch : ''; ?> />
                    <b>Default Mode</b> - Automatic Ads Placement (Recommended)
                    <blockquote class="bubble">
                        This standard mode is suitable for most of WordPress themes on the websites.<br>
                        Ads appear as one block under an article or below the first article on the page with a list of articles.
                    </blockquote>
                </div>
                <div><input type="radio" name="links_show_by" value="1" <?php echo $this->plugin_show_by == 1 ? $ch : ''; ?> />
                    <b>Widget Mode</b> - Requires Manual Adjustments (For Experienced Users only)
                    <blockquote class="bubble">For correct operation, this mode should be additionally set. <br>
                        &nbsp;&nbsp;&nbsp;<b>Step 1</b> – activate the mode and click "Save" button,<br>
                        &nbsp;&nbsp;&nbsp;<b>Step 2</b> – switch to Appearance/Widgets section,<br>
                        &nbsp;&nbsp;&nbsp;<b>Step 3</b> – drag and drop "MageNet Widget" to a proper sidebar area,<br>
                        &nbsp;&nbsp;&nbsp;<b>Step 4</b> - in the opened widget click "Save" button.
                    </blockquote>
                </div>&nbsp;<br>
                <input type="submit" name="submit_links_show_by" value="Save" style="width: 110px; margin-right: 12px;" />
                <?php echo (!empty($result_showBy_text) ? $result_showBy_text : ''); ?>
            </form>
        </div>
        <hr style="margin-bottom: 20px">


        <div class="tool-box">
            <h2 class="title">Active Ads:</h2>
            <table class="widefat">	 
                <thead>
                    <tr class="table-header">
                        <th>Page URL</th>
                        <th>Ads content</th>
                    </tr>
                </thead>    	  
                <tbody>
                    <?php if (is_array($link_data) && count($link_data) > 0): ?>
                        <?php foreach ($link_data as $key => $record): ?>
                            <tr>
                                <td class="url">  
                                    <?php echo $record['page_url'] ?>
                                </td>
                                <td class="link">
                                    <?php echo $record['link_html'] ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>            
                    <?php else: ?>
                        <tr>
                            <td colspan="2" style="text-align:center">No Ads</td>
                        </tr>
                    <?php endif; ?>
                </tbody>        
            </table> 
        </div>

        <br />

        <div class="tool-box">
            <form method="post" action="">
                <input type="hidden" name="update_data" value="1" />
                <input type="submit" name="submit" value="Refresh Ads"  style="width: 110px; margin-right: 12px;" />
                <?php echo (!empty($result_update_text) ? $result_update_text : ''); ?>
            </form>
        </div>
        <hr style="margin-bottom: 20px">
    <?php endif; ?>
</div>
