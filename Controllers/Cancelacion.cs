using System;
using System.Collections.Generic;
using System.Net.Http;
using System.Text;
using System.Threading;

namespace Inicio_de_sesion.Controllers
{
    class Cancelacion
    {
        public TimeSpan DefaultTimeout { get; private set; }

        private CancellationTokenSource GetCancellationTokenSource(
        HttpRequestMessage request,
        CancellationToken cancellationToken)
        {
            var timeout = request.GetTimeout() ?? DefaultTimeout;
            if (timeout != Timeout.InfiniteTimeSpan)
            {
                CancellationToken cancellationToken1 = cancellationToken;
                var cts = CancellationTokenSource
                    .CreateLinkedTokenSource(cancellationToken1);
                cts.CancelAfter(timeout);
                return cts;
            }
            else
            {
                // No need to create a CTS if there's no timeout
                return null;
            }
        }
    }
}
