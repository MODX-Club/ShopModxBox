{extends file="shop/basket/layout.tpl"}

{block name=basket_content}
    {field name=uri assign=current_uri}
 
    {*<pre>
        {print_r($Order)}
    </pre> *}

    <div class="row-fluid">
        <div class="span12">
        
            {if $Order && $Order._OrderProducts}
                
                {*
                    Получаем данные товаров
                *}
                
                {$pr_params = [
                    "where" => [
                        "id:in" => $Order.products_ids
                    ],
                    "limit" => 0
                ]}
                
                {processor action="web/catalog/products/getdata" ns="modxsite" params=$pr_params assign=products_result}
                
                
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
                                {foreach $Order._OrderProducts as $OrderProduct}
                                    {$Product = $OrderProduct._Product}
                                    {$key = $OrderProduct.id}
                                    
                                    {$object = $products_result.object[$Product.id]}
                                     
                                    <tr data-smodx-item-id="{$key}" data-smodx-item="good">            
                                        <td>
                                            <img src="{snippet name=phpthumbof params="input=`{$object.image|default:$object.imageDefault}`&options=`w=100`"}" />
                                        </td>
                                        <td>
                                            <a href="{$object.uri}">{$object.pagetitle}</a>
                                        </td>
                                        <td class="button">
                                            <input type="text" data-smodx-behav="goodNum" value="{$OrderProduct.quantity}" class="field2 input-mini" name="quantity[{$key}]">
                                        </td>
                                        <td class="cost">{$OrderProduct.price|number_format:0:"":" "} {$OrderProduct.currency_code}</td>
                                        
                                        <td><a class="btn btn-danger" data-smodx-behav="goodDel" href="javascript:;">удалить</a></td>
                                    </tr>
                                    
                                {/foreach}
                            </tbody>
                        </table>
                        
                        {*
                        
                        <pre>
                            {print_r($Order)}
                        </pre>
                        
                        *}
                        <div class="row-fliud">
                            <div class="span2 offset10">
                            {$basket.discount}
                                <div data-smodx-data="cost" class="order_data">
                                    <p>В корзине:     <span class="num">{$Order.quantity}</span> <span class="text">{$Order.quantity|spell:"товар":"товара":"товаров"}</span></p>
                                    <p>Сумма заказа: <span><s class="cost_original">{if $Order.discount}{((float)$Order.original_sum)|number_format:0:".":" "}{/if}</s></span> <span class="cost">{$Order.sum|number_format:0:".":" "}</span> руб.</p>
                                </div>
                                
                            </div>
                        </div>
                        
                        <div class="row-fliud">
                            <div class="span2 offset10">
                            
                                <div class="order_control" data-smodx-basket-control="rulers">
                                    <input type="hidden" name="basket_action" value="recalculate" />
                                    
                                    <input type="submit" data-smodx-behav="recount" class="btn btn-default update_button" value="Пересчитать">
                                    <a class="btn btn-success" data-smodx-behav="accept" href="{$modx->makeUrl(83)}"><span>Оформить заказ</span></a>
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
