<table class="table" style="">
    <thead>
        <tr>
            <th style="text-align:center;">{l s='Start' mod='ecordersandstock'}</th>
            <th style="text-align:center;">{l s='End' mod='ecordersandstock'}</th>
            <th style="text-align:center;">{l s='State' mod='ecordersandstock'}</th>
            <th style="text-align:center;">{l s='Step' mod='ecordersandstock'}</th>
            <th style="text-align:center;">{l s='Counter' mod='ecordersandstock'}</th>
            <th style="text-align:center;">{l s='Max' mod='ecordersandstock'}</th>
            <th style="text-align:center;">{l s='Message' mod='ecordersandstock'}</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td style="text-align:center;">{$cpanelData.start_time}</td>
            <td style="text-align:center;">{$cpanelData.end_time}</td>
        {if $cpanelData.state != 'done'}
            <td style="text-align:center;">{$cpanelData.state}</td>
            <td style="text-align:center;">{$cpanelData.stage}</td>
            <td style="text-align:center;">{$cpanelData.progress}</td>
            <td style="text-align:center;">{$cpanelData.progressmax}</td>
        {else}
            <td style="text-align:center;"> </td>
            <td style="text-align:center;"> </td>
            <td style="text-align:center;"> </td>
            <td style="text-align:center;"> </td>
        {/if}
            <td style="text-align:center;"><abbr title="{$cpanelData.message}"><i class='icon-question'></i></abbr></td>
        </tr>
    </tbody>
</table>
