export const LOGIN_CLICKED = 'LOGIN_CLICKED';
export const LOGIN_CANCELED = 'LOGIN_CANCELED';
export const LOGIN_COMPLETE = 'LOGIN_COMPLETE';
export const LOGIN_ERROR = 'LOGIN_ERROR';
export const LOGOUT_CLICKED = 'LOGOUT_CLICKED';
export const LOGOUT_COMPLETE = 'LOGOUT_COMPLETE';
export const LOGOUT_ERROR = 'LOGOUT_ERROR';
export const NOTIFICATION_ADDED = 'NOTIFICATION_ADDED';
export const CLEAR_NOTIFICATIONS = 'CLEAR_NOTIFICATIONS';
export const NOTICE_CLEADER = 'NOTICE_CLEADER';
export const OWN_DATA_REQUESTED = 'OWN_DATA_REQUESTED';
export const OWN_DATA_REQUEST_SENDED = 'OWN_DATA_REQUEST_SENDED';
export const OWN_DATA_REQUEST_FAILED = 'OWN_DATA_REQUEST_FAILED';
export const OWN_DATA_REQUEST_SUCCESS = 'OWN_DATA_REQUEST_SUCCESS';
export const USER_LIST_UPDATED = 'USER_LIST_UPDATED';

export function loginClicked() {
  return {
    type: LOGIN_CLICKED
  };
}

export function loginCanceled() {
  return {
    type: LOGIN_CANCELED
  };
}

export function loginComplete() {
  return {
    type: LOGIN_COMPLETE
  };
}

export function loginError() {
  return {
    type: LOGIN_ERROR
  };
}

export function logout() {
  return {
    type: LOGOUT_CLICKED
  };
}

export function logoutComplete() {
  return {
    type: LOGOUT_COMPLETE
  };
}

export function logoutFailed() {
  return {
    type: LOGOUT_ERROR
  };
}

export function addNotification(notification) {
  return {
    type: NOTIFICATION_ADDED,
    notification
  };
}

export function ClearNotifications() {
  return {
    type: CLEAR_NOTIFICATIONS
  };
}

export function ClearNotice(item) {
  return {
    type: NOTICE_CLEADER,
    item
  };
}

export function GetOwnData() {
  return {
    type: OWN_DATA_REQUESTED,
  };
}

export function RequestForUserDataSended() {
  return {
    type: OWN_DATA_REQUEST_SENDED,
  };
}

export function GetOwnDataFailed(response) {
  return {
    type: OWN_DATA_REQUEST_FAILED,
    response
  };
}

export function GetOwnDataSuccess(user) {
  return {
    type: OWN_DATA_REQUEST_SUCCESS,
    user
  };
}

export function UpdateUsersList(users) {

  return {
    type: USER_LIST_UPDATED,
    users,
  };
}
