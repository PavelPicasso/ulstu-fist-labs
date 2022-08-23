using System;

/// The 'Subject interface
public interface IClient
{
    string GetData();
}

/// The 'RealSubject' class
public class RealClient : IClient
{
    string Data;
    public RealClient()
    {
        Console.WriteLine("Real Client: Initialized");
        Data = "Dot Net Tricks";
    }

    public string GetData()
    {
        return Data;
    }
}

/// The 'Proxy Object' class
public class ProxyClient : IClient
{
    RealClient client = new RealClient();
    public ProxyClient()
    {
        Console.WriteLine("ProxyClient: Initialized");
    }

    public string GetData()
    {
        return client.GetData();
    }
}

/// Proxy Pattern Demo
class Program
{
    static void Main(string[] args)
    {
        ProxyClient proxy = new ProxyClient();
        Console.WriteLine("Data from Proxy Client = {0}", proxy.GetData());

        Console.ReadKey();
    }
}