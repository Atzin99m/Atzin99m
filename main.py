import sys
from PySide6.QtWidgets import QApplication, QMainWindow
from PySide6.QtUiTools import QUiLoader
from PySide6.QtCore import QFile

app = QApplication(sys.argv)

# Cargar archivo .ui
ui_file = QFile("ventana.ui")
loader = QUiLoader()
ventana = loader.load(ui_file)
ui_file.close()

ventana.show()
sys.exit(app.exec())
