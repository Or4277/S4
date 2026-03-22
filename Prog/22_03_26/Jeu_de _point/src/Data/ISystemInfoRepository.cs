using System;
using System.Threading;
using System.Threading.Tasks;

namespace Data;

public interface ISystemInfoRepository
{
    Task<DateTime> GetServerTimeAsync(CancellationToken cancellationToken = default);
}
