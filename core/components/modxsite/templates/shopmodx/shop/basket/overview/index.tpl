{extends file="shop/basket/layout.tpl"}

{block name=basket_content}
    {field name=uri assign=current_uri}
 
    {*<pre>
        {print_r($basket_result)}
    </pre> *}

    <div class="row-fluid">
        <div class="span12">
        
            {if $basket_result.success && $basket_result.object}
            
                <div data-smodx-basket="order">
            
                    <form name="order_form" action="" method="post">        
                        
                        <table class="table table-striped sost">
                            <thead>
                                <tr>
                                    <td class="title">Изображение</td>
                                    <td class="title" width="30%">Наименование</td>
                                    <td class="title" width="20%">Количество</td>
                                    <td class="title" width="18%">Цена</td>
                                    {*<td class="title" width="18%">Сумма, руб</td>*}
                                    <td class="title" width="7%">Удалить</td>
                                </tr>
                            </thead>
                            <tbody> 
                                {foreach $basket_result.object as $object}
                                    {$key = $object.id}
                                     
                                    <tr data-smodx-item-id="{$key}" data-smodx-item="good">            
                                        <td>
                                            <img src="{snippet name=phpthumbof params="input=`$object.image`&options=`w=100`"}" />
                                        </td>
                                        <td>
                                            <a href="{$object.uri}">{$object.pagetitle}</a>
                                        </td>
                                        <td class="button">
                                            <input type="text" data-smodx-behav="goodNum" value="{$object.quantity}" class="field2 input-mini" name="quantity[{$key}]">
                                        </td>
                                        <td class="cost">{$object.price|number_format:0:"":" "} {$object.currency_code}</td>
                                        
                                        <td><a class="btn btn-danger" data-smodx-behav="goodDel" href="{$current_uri}?basket_action=remove_product&product_key={$key}">удалить</a></td>
                                    </tr>
                                    
                                {/foreach}
                            </tbody>
                        </table>
                        
                        <div class="row-fliud">
                            <div class="span2 offset10">
                            
                                <div data-smodx-data="cost" class="order_data">
                                    <p>В корзине:     <span class="num">{$basket_result.quantity}</span> <span class="text">{$basket_result.quantity|spell:"товар":"товара":"товаров"}</span></p>
                                    <p>Сумма заказа: <span class="cost">{$basket_result.sum|number_format:0:".":" "}</span> руб.</p>
                                </div>
                                
                            </div>
                        </div>
                        
                        <div class="row-fliud">
                            <div class="span2 offset10">
                            
                                <div class="order_control" data-smodx-basket-control="rulers">
                                    <input type="hidden" name="basket_action" value="recalculate" />
                                    
                                    <input type="submit" data-smodx-behav="recount" class="btn update_button" value="Пересчитать">
                                    <a class="btn btn-success" data-smodx-behav="accept" href="{link id=83}"><span>Оформить заказ</span></a>
                                    <a class="btn btn-warning" data-smodx-behav="clear" href="{$current_uri}?basket_action=empty_basket"><span>Очистить корзину</span></a>  
                                </div>
                                
                            </div>
                        </div>
                        
                    </form>
                </div>
        
            {else}
                <div class="alert alert-warning">
                    <h4>Корзина пуста</h4>
                </div>
            {/if}
        </div>
    </div>
     
{/block}
