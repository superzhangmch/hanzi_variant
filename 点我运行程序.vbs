Set ws = CreateObject("Wscript.Shell") 
ws.run "cmd /c mongoose-2.10.exe mon.conf",vbhide 
msgbox "������������в�ѯ�ˣ�" 
ws.run "iexplore http://127.0.0.1:8087/yiti.php"