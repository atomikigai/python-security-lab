<?php
if (strpos($message, "/panel") === 0 || strpos($message, "!panel") === 0 || strpos($message, ".panel") === 0) {
    is_duro();
    
    $meg = "🌟 PANEL DE VENDEDOR 🌟
    
🔹 Comandos Disponibles:
    
[<a href='https://example.invalid/legacy'>ϟ</a>] .ucrd
   - Descripción: <code>Eliminar créditos</code>
   - Ejemplo: <code>.ucrd 5168647868</code>
   
[<a href='https://example.invalid/legacy'>ϟ</a>] .cred
   - Descripción: <code>Añadir créditos</code>
   - Ejemplo: <code>.cred 5168647868|111</code>
   - Ejemplo Avanzado: <code>.cred 5168647868|1999999|30d (créditos con días)</code>
   
[<a href='https://example.invalid/legacy'>ϟ</a>] /gn
   - Descripción: <code>Crear key</code>
   - Ejemplo: <code>.gn 3d (días), /gn 4m (minutos), /gn 6h (horas)</code>
   
[<a href='https://example.invalid/legacy'>ϟ</a>] /neo
   - Descripción: <code>Crear key </code>
   - Ejemplo: <code>.neo 3d, /neo 4m, /neo 6h</code>
   
[<a href='https://example.invalid/legacy'>ϟ</a>] /ban
   - Descripción: <code>Banear usuario</code>
   - Ejemplo: <code>.ban 5168647868</code>
   
[<a href='https://example.invalid/legacy'>ϟ</a>] /unban
   - Descripción: <code>Desbanear usuario</code>
   - Ejemplo: <code>.unban 5168647868</code>
   
🔹 Gestión de Grupos:
    
[<a href='https://example.invalid/legacy'>ϟ</a>] .gropmod
   - Descripción: <code>Añadir grupos</code>
   - Ejemplo: <code>.gropmod -REDACTED_NUMERIC_IDENTIFIER,30d</code>
   
[<a href='https://example.invalid/legacy'>ϟ</a>] .pdelete
   - Descripción: <code>Eliminar premium</code>
   - Ejemplo: <code>.pdelete 5168647868</code>
   
[<a href='https://example.invalid/legacy'>ϟ</a>] .cdelete
   - Descripción: <code>Eliminar grupos</code>
   - Ejemplo: <code>.cdelete -REDACTED_NUMERIC_IDENTIFIER</code>
    
🔔 ¡Utiliza estos comandos con precaución!";

    bot('sendMessage', [
        'chat_id' => $chatId,
        'disable_web_page_preview' => true,
        'reply_to_message_id' => $message_id,
        'parse_mode' => 'HTML',
        'text' => "<b>$meg</b>"
    ]);
}
?>
