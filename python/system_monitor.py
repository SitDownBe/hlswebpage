import psutil

cpu_percent = list()
mem_percent = list()

ffmpeg_cpu_percent = list()
ffmpeg_mem_percent = list()
ffmpeg_p = 0

ffmpeg = False;

print("Collecting data...")
		
for x in range(1000):
	cpu_percent.append(psutil.cpu_percent(interval=1))
	mem_percent.append(psutil.virtual_memory().percent)
	if (not ffmpeg):
		ffmpeg_cpu_percent.append(0.0)
		ffmpeg_mem_percent.append(0.0)
		for pid in psutil.pids():
			p = psutil.Process(pid)
			if p.name() == 'ffmpeg':
				ffmpeg_p = p
				ffmpeg = True
	elif (not ffmpeg_p.is_running()):
		ffmpeg_cpu_percent.append(0.0)
		ffmpeg_mem_percent.append(0.0)
	else:
		ffmpeg_cpu_percent.append(ffmpeg_p.cpu_percent()/4)
		ffmpeg_mem_percent.append(ffmpeg_p.memory_percent())
		
print("Done. Writing JSON files.")

# Open files to write JSON data.
cpu_json = open("cpu_info.json", "w+")
mem_json = open("mem_info.json", "w+")
ffmpeg_cpu_json = open("ffmpeg_cpu_info.json", "w+")
ffmpeg_mem_json = open("ffmpeg_mem_info.json", "w+")

# Write data.
cpu_json.write(str(cpu_percent))
mem_json.write(str(mem_percent))
ffmpeg_cpu_json.write(str(ffmpeg_cpu_percent))
ffmpeg_mem_json.write(str(ffmpeg_mem_percent))

# Close files.
cpu_json.close()
mem_json.close()
ffmpeg_cpu_json.close()
ffmpeg_mem_json.close()
