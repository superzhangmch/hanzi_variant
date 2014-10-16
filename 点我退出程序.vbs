Set ws = CreateObject("Wscript.Shell") 
ws.run "cmd /c taskkill /F /im mongoose-2.10.exe",vbhide 
msgbox "成功退出！还可以点右下角的有m字母的图标而退出" 