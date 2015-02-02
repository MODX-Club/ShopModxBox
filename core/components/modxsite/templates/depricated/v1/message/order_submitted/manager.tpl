{*
    Шаблон письма заказа, которое отправляется менеджерам
*}

{extends file="message/order_submitted/layout.tpl"}



{block name=body}

    {*
        Все возможные данные для оформления письма:
        <pre>
            Order. Текущий объект заказа
            {print_r($order)}
            
            OrderData. Полные данные заказа (сумма заказа, товары и т.п.)
            {print_r($order_data)}
            
            Properties. Свойства текущего процессора, включая переданные данные из формы
            {print_r($properties)}
            
            Contractor. Объект контрагента (modUser)
            {print_r($Contractor)}
            
            ContractorProfile. Профиль контрагента (modUserProfile)
            {print_r($ContractorProfile)}
        </pre>
        
        Можно раскомментировать этот блок, и в письме вы получите все данные в структурированном виде.
    *}


    <p style="font-weight: bold;">Данные покупателя:</p>
    <table border="1" width="90%">
        <tbody>  
            <tr>
                <td width="30%">ФИО: </td>
                <td>{$ContractorProfile.fullname}</td>
            </tr>
                
            <tr>
                <td width="30%">Email: </td>
                <td>{$ContractorProfile.email|default:$properties.email}</td>
            </tr>
                
            <tr>
                <td width="30%">Телефон: </td>
                <td>{$ContractorProfile.phone|default:$properties.phone}</td>
            </tr>
                
            <tr>
                <td width="30%">Мобильный телефон: </td>
                <td>{$ContractorProfile.mobilephone|default:$properties.mobilephone}</td>
            </tr>
                
            <tr>
                <td width="30%">Адрес доставки: </td>
                <td>{$properties.address}</td>
            </tr>
              
                
            <tr>
                <td width="30%">Комментарии: </td>
                <td>{$properties.comments}</td>
            </tr>  
            
        </tbody>
    </table>
    <br /><br />
{/block}

 
