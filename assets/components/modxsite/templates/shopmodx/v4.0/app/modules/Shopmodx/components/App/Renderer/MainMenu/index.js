import React, {Component} from 'react';

import PropTypes from 'prop-types';

import { Link } from 'react-router';

import Grid from 'material-ui/Grid';
import IconButton from 'material-ui/IconButton';
import LoginIcon from 'material-ui-icons/PermIdentity';
import AddIcon from 'material-ui-icons/Add';
import AtentionIcon from 'material-ui-icons/ErrorOutline';

import Avatar from 'modules/Site/components/fields/User/avatar.js';

import WsProxy from 'modules/Shopmodx/components/WsProxy';

import cookies from 'js-cookie';

export default class MainMenu extends Component{

  static contextTypes = {
    user: PropTypes.object.isRequired,
    userActions: PropTypes.object.isRequired,
    localQuery: PropTypes.func.isRequired,
    remoteQuery: PropTypes.func.isRequired,
    router: PropTypes.object.isRequired,
    document: PropTypes.object.isRequired,
    menuItems: PropTypes.array.isRequired,
  };


  static defaultProps = {
    title: "ShopModxBox",
  };

	constructor(props){

		super(props);

		this.state = {
			ratingsOpened: false,
			citiesOpened: false,
		}
	}
 
 

  componentDidUpdate(prevProps, prevState, prevContext){
 

    super.componentDidUpdate && super.componentDidUpdate(prevProps, prevState, prevContext);

  }
 


  // loadData(){

  //   const {
  //     localQuery,
  //     coords,
  //   } = this.context;

  //   localQuery({
  //     operationName: "MainMenuData",
  //     variables: {
  //       limit: 0,
  //       resourcesCenter: coords,
  //     },
  //   })
  //   .then(r => {

  //     const {
  //       ratings,
  //       resources: cities,
  //     } = r.data;



  //     this.setState({
  //       ratings,
  //       cities,
  //     });

  //   })
  //   .catch(e => {
  //     console.error(e);
  //   });

  // }
  
 


	render(){

    const {
      title,
    } = this.props;

    const {
      user: {
        user,
      },
      userActions,
      menuItems,
    } = this.context;

    const {
      username,
    } = user || {};

		let {
		} = this.state;

    let base_url = "/";


		return <div 
      className="navbar navbar-default navbar-fixed-top"
      style={{
        marginBottom: 0,
      }}
    >
      <div className="container">
        <div className="navbar-header">
          <Link 
            href={base_url}
            to={base_url}
            // href={"/"}
            // to={"/"}
            className="navbar-brand"
            title
          >
            <div className="logo">
              <i className="str leaf leaf-l"></i>
            </div>
          </Link>
        </div> 

        <div 
          id="navbar-main" 
          className="collapse navbar-collapse navbar-right"
          style={{
            display: "flex",
            flexDirection: "row",
            alignItems: "center",
            padding: 10,
          }}
        > 
          
          {menuItems && menuItems.map(n => {

            const {
              id,
              pagetitle,
              menutitle,
              uri,
            } = n;

            /*
              За яндексом было замечено, что он неправильно индексирует относительные ссылки на реакт-сайтах
            */
            const link = `/${uri}`;

            return <Link
              key={id}
              to={link}
              href={link}
              title={pagetitle}
              style={{
                padding: 5,
              }}
            >
              {menutitle || pagetitle}
            </Link>

          }) || null}

          <div
            style={{
              marginLeft: 10,
            }}
          >
            
            {user
              ?

              <span
                style={{
                  display: "flex",
                  flexDirection: "row",
                  alignItems: "center",
                }}
              >
                
                <a 
                  id="office" 
                  href="javascript:;" 
                  data-toggle="dropdown" 
                  className="dropdown-toggle flex align-center"
                  style={{
                    display: "flex",
                    marginRight: 5,
                  }}
                >
                  <Avatar 
                    user={user}
                    style={{
                      width: 20,
                      height: 20,
                      fontSize: "18px",
                    }}
                  />

                </a>

                <a 
                  href="javascript:;"
                  onClick={e => {
                    userActions.logout();
                  }}
                >
                  <i className="glyphicon glyphicon-log-out"></i> Выйти
                </a>
                
              </span>

              :
              <a 
                href="javascript:;" 
                rel="nofollow"
                onClick={event => {
                  userActions.loginClicked();
                }}
              >
                <Grid 
                  container
                  gutter={0}
                  align="center"
                >
                  <LoginIcon 
                    style={{
                      height: 16,
                      width: 16,
                    }}
                  />  Войти
                </Grid>
              </a>
              
            }

          </div>

 
          {/*
            Этот элемент удалять нельзя, выполняет часть функций с авторизацией пользователя
          */}

          <WsProxy
          />
          
        </div>

      </div>
    </div>;
	}
}