import * as env from '../actions/proxyActions';


const initialState = {

  // Флаг, что разрешено соединение с сервером
  connection_allowed: 1,

  // Необходимо соединение (запрошено сторонним объектом)
  connection_requested: false,

  // Запрошен дисконнект
  disconnection_requested: false,

  // Происходит попытка соединения
  connection_in_progress: false,

  // Попытка соединения не удалась
  connection_failed: false,

  // Состояние соединения
  connected: false,

  // Лимит попыток соединения
  attempts_limit: 10,

  count_attempts: 0,

  outbox: [],

  inbox: [],

  chat_messages: [],

};


/*
* connection_allowed - флаг, что соединение разрешено. Это уже решает сам пользователь.
* Если connection_allowed == false, то запросы на соединение игнорируются.
*
* Если запрошен дисконнект, то приложение самостоятельно не установит соединение,
* пока пользователь сам не разрешит коннект, то есть если connection_allowed == false и
* disconnection_requested == true,соединение не может быть установлено.
* Чтобы восстановить соединение, должно быть установлено disconnection_requested == true,
* в следствии чего восстанавливается connection_allowed = true
*
* */

export default function (state = initialState, action) {


  var st = Object.assign({},state);

  switch (action.type) {

    case env.CONNECTION_ALLOWED:

      st.connection_allowed = true;
      st.connection_requested = true;
      st.disconnection_requested = false;

      st.count_attempts++;
      break;

    case env.CONNECTION_DISALLOWED:

      st.connection_allowed = false;
      st.connection_requested = false;
      st.disconnection_requested = true;

      break;


    case env.CONNECTION_REQUESTED:

      st.connection_requested = true;
      st.disconnection_requested = false;

      st.count_attempts++;

      if(st.count_attempts > st.attempts_limit){
        st.connection_allowed = false;
        st.count_attempts = 0;
      }
      break;

    case env.DISCONNECTION_REQUESTED:

      st.disconnection_requested = true;
      st.connection_allowed = false;
      break;


    case env.CONNECTED:

      st.connection_in_progress = false;
      st.connection_failed = false;
      st.connection_requested = false;


      st.count_attempts = 0;
      break;

    case env.DISCONNECTED:

      st.connection_in_progress = false;
      break;


    case env.CONNECTION_IN_PROGRESS:

      st.connection_in_progress = true;
      break;

    case env.CONNECTION_FAILED:

      st.connection_in_progress = false;
      st.connection_failed = true;


      break;


    case env.CONNECTION_STATE_CHANGED:

      st.connected = action.connected == true;
      st.disconnection_requested = false;
      st.connection_requested = false;
      st.connection_in_progress = false;
      break;


    case env.MESSAGE_ADDED:

      var message;

      if(typeof action.message !== "object"){
        message = {
          text: action.message
        };
      }
      else{
        message = action.message;
      }

      if(!message.ts){
        message.ts = new Date().getTime()
      }

      st.outbox.push(message);

      break;

    case env.MESSAGE_RECEIVED:

      st.inbox.push(action.message);
      break;

    case env.MESSAGE_SENDED:

      for(var i in st.outbox){
        var message = st.outbox[i];

        if(message.ts == action.message.ts){
          message.sended = true;
        }
      }
      break;


    case env.MESSAGE_READED:

      for(var i in st.inbox){
        var message = st.inbox[i];

        if(message.ts == action.message.ts){
          message.readed = true;
        }
      }
      break;


    default:
      ;
  }
  return st
}
