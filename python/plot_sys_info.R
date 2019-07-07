# Requires the 'rjson' package to be installed.
library("rjson")

cpu_info <- fromJSON(file = "/var/www/html/hlswebpage/python/cpu_info.json")
mem_info <- fromJSON(file = "/var/www/html/hlswebpage/python/mem_info.json")
ffmpeg_cpu_info <- fromJSON(file = "/var/www/html/hlswebpage/python/ffmpeg_cpu_info.json")
ffmpeg_mem_info <- fromJSON(file = "/var/www/html/hlswebpage/python/ffmpeg_mem_info.json")

plot(cpu_info, xlab = "Seconds", ylab = "CPU%", main="Total CPU usage")
#plot(mem_info, xlab = "Seconds", ylab = "Memory%", ylim=range(0:100))
plot(mem_info, xlab = "Seconds", ylab = "Memory%", main="Total memory usage")

plot(ffmpeg_cpu_info, xlab = "Seconds", ylab = "CPU%", main="FFmpeg (thread 1) CPU usage")
plot(ffmpeg_mem_info, xlab = "Seconds", ylab = "Memory%", main="FFmpeg (thread 1) memory usage")