Follow the steps for proper installation of the Plugin :

1. Change the Environment to Development Mode.

2. In the Development Mode you will find two menus:

   a) Plugin Manager
   b) Menu Manager

3. Navigate to the Plugin Manager.

4. Click on the Upload Zip button for installing the Plugin.

5. Upload the Plugin Zip.

6. If all is ok then you will see the Plugin in the list of the plugin manager with deactivate status.

7. Activate the Plugin in the list of plugin manager.

8. If all is ok then you will see that your plugin is activated and a menu for your plugin will come.

9. If you do not find the menu then you can add the menu manually from the Menu Manager.

10. Before Restrict Controller Action  first created a Permisson group.

11. Then Assign Admin to a specific role group.

12.If you want to add role restriction then first load the helper admin_permission.

   $this->load->helper('subadmin/admin_permission');

13. There are 5 Permission . Add (A), Edit (E),Delete (D),View (V),Status Change (S). To Check restriction call admin_permission function with proper permission key. 

Example : admin_permission("V","cms-pages",true) ; 

paremeter 1: If you call this function from controller method then if admin has View (V) permission of that module .Then Admin can access that method other wise admin will redirected to dashboard with error message.

paremeter 2:  Second paremeter is  mudule slug. Second parameter shold be same as value of "action" key of menu.json .

Like in this plugin value of active key is subadmin for subadmin menu and value of active key is role for role menu.

paremeter 3: Second paremeter is optional.It takes boolean value (true | false ). If you pass true then admin will redirect to dashboard if permission denied and if you pass false then it will return false if permission denied. 

14. User can also use this function in conditional statement

if(admin_permission("V"))
{
 // write youe code

}

15. Enjoy your Plugin

Thanks.
