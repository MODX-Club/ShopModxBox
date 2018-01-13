
import ShopmodxMainMenu from 'shopmodx-react/components/App/Renderer/MainMenu';

export default class MainMenu extends ShopmodxMainMenu{

 
  getMenuItems(){

    const menuItems = super.getMenuItems();
    
    const newMenu = menuItems && menuItems.map(n => n) || undefined;

    if(newMenu.findIndex(n => n.id === -100) === -1){

      newMenu.unshift({
        id: -100,
        pagetitle: "Конструктор запросов",
        uri: "db/",
      });

    }

    return newMenu;

  }


}
