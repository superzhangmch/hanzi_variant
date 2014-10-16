Set ws = CreateObject("Wscript.Shell") 
ws.run "cmd /c mongoose-2.10.exe mon.conf",vbhide 
msgbox "可以在浏览器中查询了！" 
ws.run "iexplore http://127.0.0.1:8087/yiti.php"