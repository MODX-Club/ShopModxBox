import * as env from '../actions/userActions';


const initialState = {
  user: false,
  notifications: [],
  inProcess: false,
  logoutRequested: false,
  loginModalOpened: false,
  modifiedon: false,
  own_data_requested: false,
  users: [],
};


function RestoreState(st, data){

  st.user = false;

  return st;
}

export default function (state = initialState, action) {

  var st = Object.assign({},state);
  st.evt = action.type;
  st.modifiedon = new Date().getTime();

  switch (action.type) {

    case env.LOGIN_CLICKED:

      st.loginModalOpened = true;
      break;

    case env.LOGIN_CANCELED:

      st.loginModalOpened = false;
      break;

    case env.LOGIN_COMPLETE:

      st = Object.assign(st,{
        inProcess: false,
        loginModalOpened: false,
      });
      break;

    case env.LOGIN_ERROR:

      st.inProcess = false;
      break;


    case env.LOGOUT_CLICKED:

      st.logoutRequested = true;
      break;


    case env.LOGOUT_COMPLETE:

      st.logoutRequested = false;

      st = RestoreState(st);
      break;

    case env.LOGOUT_ERROR:
      st.logoutRequested = false;
      break;

    case env.NOTIFICATION_ADDED:
      var notification;

      if(typeof action.notification !== "object"){
        notification = {
          text: action.notification
        };
      }
      else{
        notification = action.notification;
      }

      if(!notification.ts){
        notification.ts = new Date().getTime()
      }

      st.notifications.push(notification);

      break;

    case env.CLEAR_NOTIFICATIONS:
      st.notifications = [];
      break;

    case env.NOTICE_CLEADER:
      var item = action.item;

      for(var i in st.notifications){
        if(item.ts == st.notifications[i].ts){
          st.notifications.splice(i,1);
          break;
        }
      }

      break;

    case env.OWN_DATA_REQUESTED:
      st.own_data_requested = true;
      break;

    case env.OWN_DATA_REQUEST_SENDED:
      st.own_data_requested = false;
      break;

    case env.OWN_DATA_REQUEST_FAILED:
      st.own_data_requested = false;

      break;

    case env.OWN_DATA_REQUEST_SUCCESS:
      st.own_data_requested = false;

      st.user = action.user;
      break;



    case env.USER_LIST_UPDATED:

      st.users = action.users;


      // Проверяем есть ли текущий пользователь в списке активных пользователей
      if(st.user && st.user.id){
        var finded = false;

        for(var i in st.users){
          if(st.users[i].id == st.user.id){
            finded = true;
            break;
          }
        }

        if(!finded){
          st = RestoreState(st);
        }
      }

      break;

    default:
      ;
  }

  return st;
}
