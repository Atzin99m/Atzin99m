import java.awt.image.BufferedImage;
import java.io.IOException;
import java.net.MalformedURLException;
import javax.swing.*;
import com.github.sarxos.webcam.Webcam;


/**
 *
 * @author Atzin Mauricio Luna
 */
public class WebCamflash {

    public static void main(String[] args) {
         Webcam webcam = Webcam.getDefault();
    webcam.open();
    JFrame frame = new JFrame("Webcam IP Capture");
    JLabel label = new JLabel();
    frame.add(label);
    frame.setDefaultCloseOperation(JFrame.EXIT_ON_CLOSE);
    frame.pack();
    frame.setVisible(true);

    Thread t = new Thread() {
      @Override public void run() {
        while (true) {
          BufferedImage image = webcam.getImage();
          label.setIcon(new ImageIcon(image));
          frame.pack();
          try {
            Thread.sleep(5000);
          } catch (InterruptedException e) {
            e.printStackTrace();
          }
        }
      }
    };
    t.start();
    }
}
