// import { combineReducers } from 'redux';
import userReducer from './userReducer';
import proxyReducer from './proxyReducer';
import documentReducer from './documentReducer';

module.exports = {
  user: userReducer,
  proxy: proxyReducer,
  document: documentReducer,
};
