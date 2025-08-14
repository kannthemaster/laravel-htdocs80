import javax.smartcardio.*;
import java.util.List;

public class CardReaderServer {
    public static void main(String[] args) {
        try {
            // 1. ค้นหาเครื่องอ่านบัตร
            TerminalFactory factory = TerminalFactory.getDefault();
            List<CardTerminal> terminals = factory.terminals().list();

            if (terminals.isEmpty()) {
                System.out.println("ไม่พบเครื่องอ่านบัตร");
                return;
            }

            CardTerminal terminal = terminals.get(0);
            System.out.println("ใช้เครื่องอ่าน: " + terminal.getName());

            // 2. รอจนกว่าจะมีบัตรเสียบ
            if (!terminal.isCardPresent()) {
                System.out.println("กรุณาเสียบบัตรประชาชน");
                terminal.waitForCardPresent(0); // รอไม่จำกัดเวลา
            }

            // 3. เชื่อมต่อกับบัตร
            Card card = terminal.connect("T=0");
            CardChannel channel = card.getBasicChannel();

            // 4. ส่ง APDU เลือก Thai ID card application
            byte[] selectCommand = {
                (byte) 0x00, (byte) 0xA4, (byte) 0x04, (byte) 0x00,
                (byte) 0x08, // ความยาวของ AID
                (byte) 0xA0, (byte) 0x00, (byte) 0x00, (byte) 0x00,
                (byte) 0x54, (byte) 0x48, (byte) 0x00, (byte) 0x01
            };
            ResponseAPDU response = channel.transmit(new CommandAPDU(selectCommand));
            System.out.println("เลือก Thai ID app: " + toHex(response.getBytes()));

            // 5. อ่านเลขบัตรประชาชน (CID) (ตำแหน่งข้อมูลอยู่ที่ 0x00 0x00 0x00 0x01 ความยาว 13)
            byte[] cidCommand = {
                (byte) 0x80, (byte) 0xB0, (byte) 0x00, (byte) 0x04,
                (byte) 0x02, (byte) 0x00, (byte) 0x0D
            };
            ResponseAPDU cidResponse = channel.transmit(new CommandAPDU(cidCommand));
            String cid = new String(cidResponse.getData(), "TIS-620");
            System.out.println("เลขบัตรประชาชน: " + cid);

            // ปิดการเชื่อมต่อ
            card.disconnect(false);
        } catch (Exception e) {
            e.printStackTrace();
        }
    }

    private static String toHex(byte[] bytes) {
        StringBuilder sb = new StringBuilder();
        for (byte b : bytes) sb.append(String.format("%02X ", b));
        return sb.toString().trim();
    }
}
