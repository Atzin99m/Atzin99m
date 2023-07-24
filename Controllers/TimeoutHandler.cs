using System;
using System.Threading;
using System.Net.Http;
using System.Threading;
using System.Threading.Tasks;

namespace Inicio_de_sesion.Controllers
{
    class TimeoutHandler : DelegatingHandler
    {

        protected async override Task<HttpResponseMessage> SendAsync(
      HttpRequestMessage request,
      CancellationToken cancellationToken)
        {
            using (var cts = GetCancellationTokenSource(request, cancellationToken))
            {
                try
                {
                    return await base.SendAsync(
                        request,
                        cts?.Token
                        ?? cancellationToken);
                }
                catch (OperationCanceledException)
                    when (!cancellationToken.IsCancellationRequested)
                {
                    throw new TimeoutException();
                }
            }
        }

    }

}

